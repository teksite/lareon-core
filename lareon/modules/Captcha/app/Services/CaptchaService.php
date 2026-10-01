<?php

namespace Lareon\Modules\Captcha\App\Services;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;
use Lareon\Modules\Captcha\App\Contracts\CaptchaDriver;
use Random\RandomException;
use RuntimeException;

/**
 * Multi-instance captcha.
 *
 * Every captcha gets its own random token. The token (not the session) links
 * an image to its answer, so any number of captchas can live on one page and
 * each one is validated independently.
 *
 * What is stored server side (cache, with TTL):
 *   captcha:{token}      HMAC of the answer + preset name + case flag
 *   captcha:{token}:img  the rendered JPEG (base64)
 * The plain answer is never stored and never sent to the client.
 */
class CaptchaService implements CaptchaDriver
{
    public const TOKEN_PATTERN = '/^[a-f0-9]{40}$/';

    private const PRESET_PATTERN = '/^[A-Za-z0-9_-]{1,32}$/';
    private const MAX_ANSWER_LENGTH = 64;
    private const DEFAULT_CHARACTERS = ['2', '3', '4', '6', '7', '8', '9'];

    /** @var array<string, CaptchaOptions> */
    private array $presets = [];

    private ?CaptchaHtml $html = null;

    public function __construct(
        private readonly CacheRepository  $cache,
        private readonly ConfigRepository $config,
        private readonly CaptchaRenderer  $renderer,
    ) {
    }

    /* ---------------------------------------------------------------------
     | Creating captchas
     | ------------------------------------------------------------------ */

    /**
     * Create a new captcha.
     *
     * @param string $preset Name of a preset from config('captcha.presets')
     * @param bool   $inline Also return the image as a data URI (useful for SPAs / mobile apps)
     * @return array{token: string, src: string, expires_in: int, img?: string}
     * @throws InvalidArgumentException when the preset does not exist
     */
    public function make(string $preset = 'default', bool $inline = false): array
    {
        $options = $this->preset($preset);

        [$display, $answer] = $this->generateText($options);

        $token = bin2hex(random_bytes(20));
        $jpeg = $this->renderer->render($options, $display);

        $this->cache->put($this->payloadKey($token), [
            'hash'      => $this->hash($token, $this->normalize($answer, $options->sensitive)),
            'sensitive' => $options->sensitive,
            'preset'    => $preset,
        ], $options->expire);
        $this->cache->put($this->imageKey($token), base64_encode($jpeg), $options->expire);

        $challenge = [
            'token'      => $token,
            'src'        => $this->src($token),
            'expires_in' => $options->expire,
        ];

        if ($inline) {
            $challenge['img'] = 'data:image/jpeg;base64,' . base64_encode($jpeg);
        }

        return $challenge;
    }

    /**
     * JPEG bytes of an existing captcha, or null when it is unknown / expired.
     * Reading the image does not consume the captcha, so the <img> can be reloaded.
     */
    public function image(string $token): ?string
    {
        if (!$this->isValidToken($token)) {
            return null;
        }

        $encoded = $this->cache->get($this->imageKey($token));

        return is_string($encoded) ? (base64_decode($encoded, true) ?: null) : null;
    }

    /**
     * Forget a captcha that is no longer needed (e.g. the user asked for a new one).
     */
    public function discard(?string $token): void
    {
        if ($token !== null && $this->isValidToken($token)) {
            $this->cache->forget($this->payloadKey($token));
            $this->cache->forget($this->imageKey($token));
        }
    }

    /* ---------------------------------------------------------------------
     | Validating captchas
     | ------------------------------------------------------------------ */

    /**
     * Validate the answer of ONE captcha.
     *
     * A captcha can be checked only once: it is consumed on the first attempt,
     * right or wrong, so a single captcha can never be brute-forced.
     *
     * @param string|null $preset When given, the captcha must have been created with this preset.
     *                            Use it so a client cannot pick a weaker preset for your form.
     */
    public function check(?string $answer, ?string $token = null, ?string $preset = null): bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        if ($answer === null || $token === null || !$this->isValidToken($token)) {
            return false;
        }

        $answer = trim($answer);
        if ($answer === '' || mb_strlen($answer) > self::MAX_ANSWER_LENGTH) {
            return false;
        }

        $payload = $this->cache->get($this->payloadKey($token));
        if (!is_array($payload) || !isset($payload['hash'], $payload['sensitive'], $payload['preset'])) {
            return false;
        }

        // Consume it: whoever deletes the entry first wins (atomic on file/redis/memcached).
        if (!$this->cache->forget($this->payloadKey($token))) {
            return false;
        }
        $this->cache->forget($this->imageKey($token));

        if ($preset !== null && $payload['preset'] !== $preset) {
            return false;
        }

        $expected = $this->hash($token, $this->normalize($answer, (bool)$payload['sensitive']));

        return hash_equals((string)$payload['hash'], $expected);
    }

    /* ---------------------------------------------------------------------
     | URLs & HTML
     | ------------------------------------------------------------------ */

    public function src(string $token): string
    {
        return url(str_replace(
            '{token}',
            $token,
            (string)$this->cfg('routes.image', '/ajax/client-submitting/captcha/{token}')
        ));
    }

    public function reloadUrl(): string
    {
        return url((string)$this->cfg('routes.reload', '/ajax/client-submitting/captcha/load'));
    }

    /**
     * Ready-to-use form block: image + reload button + hidden token + answer input.
     *
     * @param array $options name, id, class, img[], input[], button[], reload_label
     */
    public function field(string $preset = 'default', array $options = []): HtmlString
    {
        return $this->html()->field($preset, $options);
    }

    /**
     * The tiny JS (reload button) - printed only once per request.
     */
    public function script(?string $nonce = null): HtmlString
    {
        return $this->html()->script($nonce);
    }

    public function name(): string
    {
        return 'local';
    }

    /**
     * @return array<string, mixed>
     */
    public function clientConfig(): array
    {
        return [
            'driver'     => 'local',
            'create_url' => $this->reloadUrl(),
        ];
    }

    public function isDisabled(): bool
    {
        return (bool)$this->cfg('disable', false);
    }

    /**
     * Options of a preset (validated and cached).
     *
     * @throws InvalidArgumentException
     */
    public function preset(string $name): CaptchaOptions
    {
        if (isset($this->presets[$name])) {
            return $this->presets[$name];
        }

        if (!preg_match(self::PRESET_PATTERN, $name)) {
            throw new InvalidArgumentException('Invalid captcha preset name.');
        }

        $values = $this->cfg('presets.' . $name);
        if (!is_array($values)) {
            throw new InvalidArgumentException("Unknown captcha preset [{$name}].");
        }

        return $this->presets[$name] = CaptchaOptions::fromArray($values);
    }

    /* ---------------------------------------------------------------------
     | Internals
     | ------------------------------------------------------------------ */

    /**
     * @return array{0: string, 1: string} [text drawn on the image, expected answer]
     * @throws RandomException
     */
    private function generateText(CaptchaOptions $options): array
    {
        if ($options->math) {
            $x = random_int(10, 30);
            $y = random_int(1, 9);

            return ["{$x}+{$y}=", (string)($x + $y)];
        }

        $characters = $this->characters($options);

        $text = '';
        for ($i = 0; $i < $options->length; $i++) {
            $text .= $characters[random_int(0, count($characters) - 1)];
        }

        if (!$options->sensitive) {
            $text = mb_strtolower($text);
        }

        return [$text, $text];
    }

    /**
     * @return string[]
     */
    private function characters(CaptchaOptions $options): array
    {
        $characters = $options->characters ?? $this->cfg('characters', self::DEFAULT_CHARACTERS);

        if (is_string($characters)) {
            $characters = mb_str_split($characters);
        }

        $characters = array_values(array_filter((array)$characters, fn($c) => is_string($c) && $c !== ''));

        return $characters !== [] ? $characters : self::DEFAULT_CHARACTERS;
    }

    /**
     * Make the comparison forgiving for humans: trim, ignore case (unless the
     * preset is case-sensitive) and accept Persian / Arabic digits.
     */
    private function normalize(string $value, bool $sensitive): string
    {
        $value = strtr(trim($value), [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        return $sensitive ? $value : mb_strtolower($value);
    }

    /**
     * Keyed hash of the answer (fast, and useless without the app key).
     */
    private function hash(string $token, string $normalizedAnswer): string
    {
        return hash_hmac('sha256', $token . '|' . $normalizedAnswer, $this->secret());
    }

    private function secret(): string
    {
        $key = (string)$this->config->get('app.key');

        if ($key === '') {
            throw new RuntimeException('Captcha: APP_KEY is not set.');
        }

        return $key;
    }

    private function isValidToken(string $token): bool
    {
        return preg_match(self::TOKEN_PATTERN, $token) === 1;
    }

    private function payloadKey(string $token): string
    {
        return 'captcha:' . $token;
    }

    private function imageKey(string $token): string
    {
        return 'captcha:' . $token . ':img';
    }

    /**
     * Read a config value from "modules.captcha.*" with fallback to "captcha.*".
     */
    private function cfg(string $key, mixed $default = null): mixed
    {
        foreach (['modules.captcha.', 'captcha.'] as $prefix) {
            if ($this->config->has($prefix . $key)) {
                return $this->config->get($prefix . $key);
            }
        }

        return $default;
    }

    private function html(): CaptchaHtml
    {
        return $this->html ??= new CaptchaHtml($this);
    }
}

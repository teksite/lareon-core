<?php

namespace Lareon\Modules\Captcha\App\Services\Drivers;

use Closure;
use Illuminate\Support\HtmlString;
use Lareon\Modules\Captcha\App\Contracts\CaptchaDriver;
use Lareon\Modules\Captcha\App\Services\HtmlAttributes;
use Lareon\Modules\Captcha\App\Services\RequestOnce;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Shared logic of captcha services that run a widget in the browser and hand
 * back a one-time response token which must be verified against the provider
 * ("siteverify"): Google reCAPTCHA v2, Cloudflare Turnstile, hCaptcha...
 *
 * Both providers are rendered EXPLICITLY and write the token into our own hidden
 * input. That gives every captcha a name of your choice, so several widgets can
 * live on one page (even inside one form) and each is validated on its own field.
 */
abstract class RemoteCaptchaDriver implements CaptchaDriver
{
    /** Upper bound for the token we are willing to forward to the provider. */
    private const MAX_TOKEN_LENGTH = 4096;

    private const LANGUAGE_PATTERN = '/^(auto|[a-zA-Z]{2,3}([-_][a-zA-Z0-9]{2,8})?)$/';

    /**
     * @param array    $settings Config of this driver (site_key, secret_key, ...)
     * @param Closure  $http     fn(string $url, array $form, int $timeout, ?string $proxy): ?array
     *                           POSTs a form and returns the decoded JSON, or null on any failure
     * @param Closure  $ip       fn(): ?string  client IP of the current request
     */
    public function __construct(
        protected readonly array            $settings,
        protected readonly Closure          $http,
        protected readonly Closure          $ip,
        protected readonly ?LoggerInterface $logger = null,
    ) {
    }

    abstract public function name(): string;

    /** Name of the browser global created by the provider script (grecaptcha / turnstile). */
    abstract protected function globalName(): string;

    abstract protected function defaultApiUrl(): string;

    abstract protected function defaultVerifyUrl(): string;

    /** @return string[] */
    abstract protected function themes(): array;

    /** @return string[] */
    abstract protected function sizes(): array;

    /**
     * Widget options (theme, size, language) that grecaptcha.render()/turnstile.render() understand.
     *
     * @return string[]
     */
    abstract protected function renderOptions(): array;

    /**
     * Extra fixed parameters for render().
     *
     * @return array<string, mixed>
     */
    protected function renderExtras(): array
    {
        return [];
    }

    /**
     * Query parameters appended to the provider script URL (besides onload/render).
     *
     * @return array<string, string>
     */
    protected function scriptQuery(): array
    {
        return [];
    }

    /* ---------------------------------------------------------------------
     | Rendering
     | ------------------------------------------------------------------ */

    public function field(string $preset = 'default', array $options = []): HtmlString
    {
        $name = (string)($options['name'] ?? 'captcha');
        $widget = $this->widgetOptions($preset, $options);

        $wrapper = HtmlAttributes::build([
            'class'                => (string)($options['class'] ?? 'captcha-field'),
            'id'                   => $options['id'] ?? null,
            'data-captcha-widget'  => true,
            'data-captcha-driver'  => $this->name(),
            'data-captcha-sitekey' => $this->siteKey(),
            'data-captcha-theme'   => $widget['theme'] ?? null,
            'data-captcha-size'    => $widget['size'] ?? null,
            'data-captcha-language' => $widget['language'] ?? null,
        ]);

        $input = HtmlAttributes::build([
            'type'                  => 'hidden',
            'name'                  => $name,
            'value'                 => '',
            'data-captcha-response' => true,
        ]);

        return new HtmlString("<div {$wrapper}><div data-captcha-target></div><input {$input}></div>");
    }

    /**
     * @throws \JsonException
     */
    public function script(?string $nonce = null): HtmlString
    {
        if (!RequestOnce::first($this->name())) {
            return new HtmlString('');
        }

        $config = json_encode([
            'name'   => $this->name(),
            'global' => $this->globalName(),
            'onload' => $this->onloadName(),
            'attrs'  => $this->renderOptions(),
            'extra'  => (object)$this->renderExtras(),
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);

        $js = str_replace('__CONFIG__', $config, self::INIT_JS);
        $attribute = $nonce !== null ? ' nonce="' . e($nonce) . '"' : '';
        $src = e($this->scriptSrc());

        // the init code must exist before the provider script calls our onload function
        return new HtmlString("<script{$attribute}>{$js}</script><script{$attribute} src=\"{$src}\" async defer></script>");
    }

    /**
     * @return array<string, mixed>
     */
    public function clientConfig(): array
    {
        return array_filter([
            'driver'   => $this->name(),
            'site_key' => $this->siteKey(),
            'script'   => $this->scriptSrc(),
            'theme'    => $this->widgetOptions('default', [])['theme'] ?? null,
            'size'     => $this->widgetOptions('default', [])['size'] ?? null,
        ], fn($value) => $value !== null);
    }

    /* ---------------------------------------------------------------------
     | Validation
     | ------------------------------------------------------------------ */

    /**
     * Verify the response token with the provider.
     *
     * Fails closed: any network error, timeout or unexpected answer means "not human".
     * The provider invalidates the token after the first verification, so every
     * token can be used only once.
     *
     * @throws RuntimeException when the secret key is not configured (a setup error should be loud)
     */
    public function check(?string $answer, ?string $token = null, ?string $preset = null): bool
    {
        if (!is_string($answer)) {
            return false;
        }

        $answer = trim($answer);
        if ($answer === '' || strlen($answer) > self::MAX_TOKEN_LENGTH) {
            return false;
        }

        $form = [
            'secret'   => $this->secretKey(),
            'response' => $answer,
        ];

        $ip = ($this->ip)();
        if ($this->setting('send_ip', true) && is_string($ip) && $ip !== '') {
            $form['remoteip'] = $ip;
        }

        $proxy = $this->setting('proxy');
        $result = ($this->http)(
            (string)$this->setting('verify_url', $this->defaultVerifyUrl()),
            $form,
            max(1, (int)$this->setting('timeout', 5)),
            is_string($proxy) && $proxy !== '' ? $proxy : null
        );

        if (!is_array($result)) {
            $this->log('warning', 'verification request failed (network error, timeout or bad response)');

            return false;
        }

        if (($result['success'] ?? false) !== true) {
            $this->logFailure($result);

            return false;
        }

        $hostnames = array_map('strtolower', array_filter((array)$this->setting('hostnames', []), 'is_string'));
        if ($hostnames !== [] && !in_array(strtolower((string)($result['hostname'] ?? '')), $hostnames, true)) {
            $this->log('warning', 'token was solved on an unexpected hostname', [
                'hostname' => is_string($result['hostname'] ?? null) ? $result['hostname'] : null,
            ]);

            return false;
        }

        return true;
    }

    /* ---------------------------------------------------------------------
     | Internals
     | ------------------------------------------------------------------ */

    protected function setting(string $key, mixed $default = null): mixed
    {
        $value = $this->settings[$key] ?? null;

        return $value === null || $value === '' ? $default : $value;
    }

    protected function siteKey(): string
    {
        $key = $this->setting('site_key');

        if (!is_string($key)) {
            throw new RuntimeException("Captcha [{$this->name()}]: site_key is not configured.");
        }

        return $key;
    }

    protected function secretKey(): string
    {
        $key = $this->setting('secret_key');

        if (!is_string($key)) {
            throw new RuntimeException("Captcha [{$this->name()}]: secret_key is not configured.");
        }

        return $key;
    }

    /**
     * Widget options = driver defaults, then the preset, then per field options.
     * Values are checked against an allow list.
     *
     * @return array{theme?: string, size?: string, language?: string}
     */
    protected function widgetOptions(string $preset, array $options): array
    {
        $presets = $this->setting('presets', []);

        $merged = array_merge(
            (array)$this->setting('widget', []),
            is_array($presets) && is_array($presets[$preset] ?? null) ? $presets[$preset] : [],
            (array)($options['widget'] ?? [])
        );

        if (!isset($merged['language'])) {
            $merged['language'] = $this->setting('language');
        }

        $result = [];

        if (in_array($merged['theme'] ?? null, $this->themes(), true)) {
            $result['theme'] = $merged['theme'];
        }
        if (in_array($merged['size'] ?? null, $this->sizes(), true)) {
            $result['size'] = $merged['size'];
        }
        if (is_string($merged['language'] ?? null) && preg_match(self::LANGUAGE_PATTERN, $merged['language'])) {
            $result['language'] = $merged['language'];
        }

        return $result;
    }

    protected function scriptSrc(): string
    {
        $url = (string)$this->setting('api_url', $this->defaultApiUrl());

        $query = http_build_query(array_merge(
            ['onload' => $this->onloadName(), 'render' => 'explicit'],
            $this->scriptQuery()
        ));

        return $url . (str_contains($url, '?') ? '&' : '?') . $query;
    }

    protected function onloadName(): string
    {
        return 'captchaRemoteReady_' . preg_replace('/[^a-z0-9]/i', '', $this->name());
    }

    protected function logFailure(array $result): void
    {
        $codes = array_values(array_filter(
            (array)($result['error-codes'] ?? []),
            fn($code) => is_string($code) && preg_match('/^[a-z0-9-]{1,64}$/i', $code)
        ));

        // a wrong / missing secret blocks every visitor: make it easy to find
        $setupError = array_intersect($codes, ['invalid-input-secret', 'missing-input-secret', 'bad-request']) !== [];

        $this->log($setupError ? 'error' : 'info', 'token rejected', ['error_codes' => $codes]);
    }

    protected function log(string $level, string $message, array $context = []): void
    {
        $this->logger?->{$level}("Captcha [{$this->name()}]: {$message}", $context);
    }

    /**
     * Browser side: renders every [data-captcha-widget] of this driver once the
     * provider script is ready and keeps the token in the widget's hidden input.
     * Also exposes window.captchaReload(el) (reset) and window.captchaRender(el).
     */
    private const INIT_JS = <<<'JS'
(function (D) {
    var registry = window.captchaRemote = window.captchaRemote || {};
    if (registry[D.name]) return;
    registry[D.name] = true;

    var SELECTOR = '[data-captcha-widget][data-captcha-driver="' + D.name + '"]';

    function api() {
        var a = window[D.global];
        return a && typeof a.render === 'function' ? a : null;
    }

    function widgets(root) {
        var scope = root && root.querySelectorAll ? root : document;
        var list = [].slice.call(scope.querySelectorAll(SELECTOR));
        if (root && root.matches && root.matches(SELECTOR)) list.push(root);
        return list;
    }

    function renderBox(box) {
        if (box.getAttribute('data-captcha-rendered')) return;
        var a = api();
        if (!a) return;
        var target = box.querySelector('[data-captcha-target]');
        var input = box.querySelector('[data-captcha-response]');
        if (!target || !input) return;

        var params = {
            sitekey: box.getAttribute('data-captcha-sitekey'),
            callback: function (token) { input.value = token || ''; },
            'expired-callback': function () { input.value = ''; },
            'error-callback': function () { input.value = ''; }
        };
        D.attrs.forEach(function (name) {
            var value = box.getAttribute('data-captcha-' + name);
            if (value) params[name] = value;
        });
        Object.keys(D.extra).forEach(function (name) { params[name] = D.extra[name]; });

        box.setAttribute('data-captcha-rendered', '1');
        try {
            box.__captchaWidgetId = a.render(target, params);
        } catch (e) {
            box.removeAttribute('data-captcha-rendered');
        }
    }

    function renderAll(root) {
        widgets(root).forEach(renderBox);
    }

    // called by the provider script when it has finished loading
    window[D.onload] = function () { renderAll(); };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { renderAll(); });
    } else {
        renderAll();
    }

    // widgets added later (AJAX / modals) are rendered automatically
    if (window.MutationObserver) {
        new MutationObserver(function () {
            if (document.querySelector(SELECTOR + ':not([data-captcha-rendered])')) renderAll();
        }).observe(document.documentElement, {childList: true, subtree: true});
    }

    var previousReload = window.captchaReload;
    window.captchaReload = function (root) {
        if (typeof previousReload === 'function') previousReload(root);
        var a = api();
        widgets(root).forEach(function (box) {
            var input = box.querySelector('[data-captcha-response]');
            if (input) input.value = '';
            if (a && box.__captchaWidgetId !== undefined && typeof a.reset === 'function') {
                a.reset(box.__captchaWidgetId);
            }
        });
    };

    var previousRender = window.captchaRender;
    window.captchaRender = function (root) {
        if (typeof previousRender === 'function') previousRender(root);
        renderAll(root);
    };
})(__CONFIG__);
JS;
}

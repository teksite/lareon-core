<?php

namespace Lareon\Modules\Captcha\App\Services;

use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Session\Store as Session;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;


class CaptchaService
{
    protected $files;
    protected $config;
    protected $session;
    protected $hasher;
    protected $str;

    protected $characters;
    protected $text;

    /**
     * @var GdImageService
     */
    protected GdImageService $image;


    protected array  $backgrounds = [];
    protected array  $fonts       = [];
    protected array  $fontColors  = [];
    protected int    $length      = 5;
    protected int    $width       = 120;
    protected int    $height      = 36;
    protected int    $angle       = 15;
    protected int    $lines       = 3;
    protected int    $lineWidth   = 2;
    protected string $lineColor   = 'ff00ff';

    protected int     $contrast        = 0;
    protected int     $quality         = 90;
    protected int     $sharpen         = 0;
    protected int     $blur            = 0;
    protected bool    $bgImage         = true;
    protected string  $bgColor         = '#ffffff';
    protected bool    $invert          = false;
    protected bool    $sensitive       = false;
    protected bool    $math            = false;
    protected int     $textLeftPadding = 4;
    protected ?string $fontsDirectory;
    protected int     $expire          = 60;
    protected bool    $encrypt         = true;
    protected int     $marginTop       = 0;
    protected string  $fill            = 'ccc';

    /**
     * @throws Exception
     */
    public function __construct(Filesystem $files, Repository $config, Session $session, Hasher $hasher, Str $str,)
    {
        $this->files = $files;
        $this->config = $config;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->str = $str;
        $this->characters = $this->cfg('characters', ['1', '2', '3', '4', '6', '7', '8', '9', '0']);
        $this->fontsDirectory = module_path('Captcha', 'resources/assets/fonts');
    }

    /**
     * Read a config value from "modules.captcha.*" with fallback to "captcha.*".
     */
    protected function cfg(string $key, $default = null,)
    {
        return config("captcha.{$key}", $default);
    }

    /**
     * @param string $config
     * @return void
     */
    protected function configure(string $config,): void
    {
        $values = $this->cfg($config);

        if (is_array($values)) {
            foreach ($values as $key => $val) {
                $this->{$key} = $val;
            }
        }
    }

    /**
     * Collect file paths of a directory filtered by extension.
     *
     * @return string[]
     */
    protected function listFiles(string $directory, array $extensions,): array
    {
        $files = array_filter(
            File::files($directory),
            fn($file,) => in_array(strtolower($file->getExtension()), $extensions, true),
        );
        return array_values(array_map(fn($file,) => $file->getPathname(), $files));
    }

    /**
     * Create captcha image
     *
     * @param string $config
     * @param bool   $api
     * @return Response|array
     * @throws Exception
     */
    public function create(string $config = 'default', bool $api = false,): Response|array
    {
        $this->backgrounds = $this->listFiles(module_path('Captcha', 'resources/assets/backgrounds'), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif']);

        $this->fonts = $this->listFiles($this->fontsDirectory, ['ttf', 'otf', 'woff', 'woff2']);

        if (empty($this->fonts)) throw new Exception('Captcha: no .ttf/.otf font found in '.$this->fontsDirectory);

        $this->configure($config);

        if ($this->bgImage && empty($this->backgrounds)) throw new Exception('Captcha: bgImage is enabled but no background image was found.');

        $generator = $this->generate();
        $this->text = $generator['value'];

        $this->image = GdImageService::canvas($this->width, $this->height, $this->bgImage ? $this->fill : $this->bgColor);

        if ($this->bgImage) $this->image->placeBackground($this->background());

        if ($this->contrast != 0) $this->image->contrast($this->contrast);

        $this->text();

        $this->lines();

        if ($this->sharpen) $this->image->sharpen($this->sharpen);

        if ($this->invert) $this->image->invert();

        if ($this->blur) $this->image->blur($this->blur);

        Cache::put($this->get_cache_key($generator['key']), $generator['value'], $this->expire);

        return $api
            ? [
                'sensitive' => $generator['sensitive'],
                'key'       => $generator['key'],
                'img'       => $this->image->toDataUri($this->quality),
            ]
            : new Response($this->image->toJpeg($this->quality), 200, [
                'Content-Type'        => 'image/jpeg',
                'Content-Disposition' => 'inline; filename="image.jpg"',
            ]);
    }

    /**
     * Image backgrounds
     */
    protected function background(): string
    {
        return $this->backgrounds[rand(0, count($this->backgrounds) - 1)];
    }

    /**
     * Generate captcha text
     *
     * @throws Exception
     */
    protected function generate(): array
    {
        $characters = is_string($this->characters) ? str_split($this->characters) : $this->characters;

        $bag = [];

        if ($this->math) {
            $x = rand(10, 30);
            $y = rand(1, 9);
            $bag = "$x + $y = ";
            $key = $x + $y;
            $key .= '';
        } else {
            for ($i = 0; $i < $this->length; $i++) {
                $char = $characters[rand(0, count($characters) - 1)];
                $bag[] = $this->sensitive ? $char : $this->str->lower($char);
            }
            $key = implode('', $bag);
        }

        $hash = $this->hasher->make($key);

        if ($this->encrypt) $hash = Crypt::encrypt($hash);

        $this->session->put('captcha', [
            'sensitive' => $this->sensitive,
            'key'       => $hash,
            'encrypt'   => $this->encrypt,
        ]);

        return [
            'value'     => $bag,
            'sensitive' => $this->sensitive,
            'key'       => $hash,
        ];
    }

    /**
     * Writing captcha text
     */
    protected function text(): void
    {
        $text = $this->text;
        if (is_string($text)) $text = str_split($text);

        $count = max(1, count($text));

        $marginTop = (int)($this->image->height() / $count);

        if ($this->marginTop !== 0) $marginTop = $this->marginTop;


        foreach ($text as $key => $char) {
            $marginLeft = (int)($this->textLeftPadding + ($key * ($this->image->width() - $this->textLeftPadding) / $count));

            $this->image->text(
                (string)$char,
                $marginLeft,
                $marginTop,
                $this->font(),
                $this->fontSize(),
                $this->fontColor(),
                $this->angle(),
            );
        }
    }

    protected function font(): string
    {
        return $this->fonts[rand(0, count($this->fonts) - 1)];
    }

    protected function fontSize(): int
    {
        return rand($this->image->height() - 10, $this->image->height());
    }

    protected function fontColor(): string
    {
        if (!empty($this->fontColors)) return $this->fontColors[rand(0, count($this->fontColors) - 1)];

        return '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    protected function angle(): int
    {
        return rand((-1 * $this->angle), $this->angle);
    }

    /**
     * Random image lines
     */
    protected function lines(): void
    {
        for ($i = 0; $i <= $this->lines; $i++) {
            $this->image->line(
                rand(0, $this->image->width()) + $i * rand(0, $this->image->height()),
                rand(0, $this->image->height()),
                rand(0, $this->image->width()),
                rand(0, $this->image->height()),
                $this->lineColor,
                $this->lineWidth,
            );
        }
    }

    /**
     * Captcha check
     */
    public function check(string $value,): bool
    {
        if (!$this->session->has('captcha')) return false;

        $key = $this->session->get('captcha.key');
        $sensitive = $this->session->get('captcha.sensitive');
        $encrypt = $this->session->get('captcha.encrypt');

        if (!Cache::pull($this->get_cache_key($key))) {
            $this->session->remove('captcha');
            return false;
        }

        if (!$sensitive) $value = $this->str->lower($value);

        if ($encrypt) $key = Crypt::decrypt($key);
        $check = $this->hasher->check($value, $key);

        if ($check) $this->session->remove('captcha');

        return $check;
    }

    /**
     * Returns the md5 short version of the key for cache
     */
    protected function get_cache_key($key,): string
    {
        return 'captcha_'.md5($key);
    }

    /**
     * Captcha check (API)
     */
    public function check_api(string $value, string $key, string $config = 'custom',): bool
    {
        if (!Cache::pull($this->get_cache_key($key))) {
            return false;
        }

        $this->configure($config);

        if (!$this->sensitive) $value = $this->str->lower($value);
        if ($this->encrypt) $key = Crypt::decrypt($key);
        return $this->hasher->check($value, $key);
    }

    /**
     * Generate captcha image source
     */
    public function src(string $config = 'default',): string
    {
        return url('/ajax/client-submitting/captcha/'.$config).'?'.$this->str->random(8);
    }

    /**
     * Generate captcha image HTML tag
     */
    public function img(string $config = 'default', array $attrs = [],): string
    {
        $attrs_str = '';
        foreach ($attrs as $attr => $value) {
            if ($attr == 'src') {
                continue;
            }

            $attrs_str .= $attr.'="'.$value.'" ';
        }
        return new HtmlString('<img src="'.$this->src($config).'" '.trim($attrs_str).'>');
    }
}

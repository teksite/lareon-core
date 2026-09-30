<?php

namespace Lareon\Modules\Captcha\App\Services;

use Illuminate\Support\HtmlString;
use Random\RandomException;

/**
 * Builds the HTML of a captcha form block.
 *
 * Field naming: the answer input is called {name} (default "captcha") and the
 * hidden token input is called {name}_token. Forms that live in separate
 * <form> tags can all use the default name; captcha that share ONE <form>
 * just need different names.
 */
class CaptchaHtml
{
    public const string TOKEN_SUFFIX = '_token';

    /** Fallback flag for contexts without a request (CLI, tests). */
    private static bool $scriptPrinted = false;

    public function __construct(private readonly CaptchaService $service) {}

    /**
     * @param array $options name, id, class, img[], input[], button[], reload_label
     * @throws RandomException
     */
    public function field(string $preset = 'default', array $options = []): HtmlString
    {
        if ($this->service->isDisabled()) {
            return new HtmlString('');
        }

        $name = (string)($options['name'] ?? 'captcha');
        $challenge = $this->service->make($preset);
        $size = $this->service->preset($preset);
        $id = (string)($options['id'] ?? 'captcha-'.substr($challenge['token'], 0, 8));

        $img = $this->tag('img', array_merge([
            'src'                => $challenge['src'],
            'alt'                => __('captcha'),
            'width'              => $size->width,
            'height'             => $size->height,
            'data-captcha-image' => true,
        ], (array)($options['img'] ?? [])));

        $button = $this->tag('button', array_merge([
            'type'                => 'button',
            'title'               => __('new captcha code'),
            'data-captcha-reload' => true,
        ], (array)($options['button'] ?? [])), e((string)($options['reload_label'] ?? '↻')));

        $token = $this->tag('input', [
            'type'               => 'hidden',
            'name'               => $name.self::TOKEN_SUFFIX,
            'value'              => $challenge['token'],
            'data-captcha-token' => true,
        ]);

        $input = $this->tag('input', array_merge([
            'type'               => 'text',
            'name'               => $name,
            'id'                 => $id,
            'autocomplete'       => 'off',
            'autocapitalize'     => 'off',
            'spellcheck'         => 'false',
            'required'           => true,
            'placeholder'        => __('captcha code'),
            'data-captcha-input' => true,
        ], (array)($options['input'] ?? [])));

        $wrapper = $this->attributes([
            'class'                   => (string)($options['class'] ?? 'captcha-field'),
            'data-captcha'            => true,
            'data-captcha-preset'     => $preset,
            'data-captcha-reload-url' => $this->service->reloadUrl(),
        ]);

        return new HtmlString("<div {$wrapper}>{$img}{$button}{$token}{$input}</div>");
    }

    /**
     * Small dependency-free script: reload button, auto reload of expired images
     * and window.captchaReload(elementOrForm) for use after AJAX validation errors.
     * It is printed only once per request, no matter how many captcha exist.
     */
    public function script(?string $nonce = null): HtmlString
    {
        if ($this->markScriptPrinted()) {
            return new HtmlString('');
        }

        $js = <<<'JS'
            (function () {
                if (window.captchaReload) return;

                function boxes(el) {
                    if (el.matches && el.matches('[data-captcha]')) return [el];
                    return [].slice.call(el.querySelectorAll('[data-captcha]'));
                }

                function reload(box) {
                    const img = box.querySelector('[data-captcha-image]');
                    const token = box.querySelector('[data-captcha-token]');
                    const input = box.querySelector('[data-captcha-input]');
                    if (!img || !token) return;

                    const url = box.getAttribute('data-captcha-reload-url')
                        + '?preset=' + encodeURIComponent(box.getAttribute('data-captcha-preset'))
                        + '&old=' + encodeURIComponent(token.value);

                    box.classList.add('is-loading');
                    fetch(url, {
                        credentials: 'same-origin',
                        headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                    })
                        .then(function (r) { return r.ok ? r.json() : Promise.reject(r); })
                        .then(function (json) {
                            token.value = json.data.token;
                            img.src = json.data.src;
                            if (input) input.value = '';
                        })
                        .catch(function () {})
                        .then(function () { box.classList.remove('is-loading'); });
                }

                document.addEventListener('click', function (e) {
                    const button = e.target.closest && e.target.closest('[data-captcha-reload]');
                    if (!button) return;
                    e.preventDefault();
                    const box = button.closest('[data-captcha]');
                    if (box) reload(box);
                });

                // image expired while the page was open -> get a fresh one (once)
                document.addEventListener('error', function (e) {
                    const img = e.target;
                    if (!img.matches || !img.matches('[data-captcha-image]') || img.dataset.retried) return;
                    img.dataset.retried = '1';
                    reload(img.closest('[data-captcha]'));
                }, true);

                window.captchaReload = function (el) {
                    (el ? boxes(el) : boxes(document)).forEach(reload);
                };
            })();
            JS;

        $nonceAttribute = $nonce !== null ? ' nonce="'.e($nonce).'"' : '';

        return new HtmlString("<script{$nonceAttribute}>{$js}</script>");
    }

    /**
     * Remember (per request, so it is safe with Octane) that the script was printed.
     *
     * @return bool true when it had already been printed
     */
    private function markScriptPrinted(): bool
    {
        $request = function_exists('request') ? request() : null;

        if (is_object($request) && isset($request->attributes)) {
            if ($request->attributes->get('captcha.script_printed')) {
                return true;
            }
            $request->attributes->set('captcha.script_printed', true);

            return false;
        }

        $printed = self::$scriptPrinted;
        self::$scriptPrinted = true;

        return $printed;
    }

    private function tag(string $name, array $attributes, ?string $content = null): string
    {
        $attributes = $this->attributes($attributes);

        return $content === null
            ? "<{$name} {$attributes}>"
            : "<{$name} {$attributes}>{$content}</{$name}>";
    }

    /**
     * Build an attribute string. Values are escaped; null/false skips an
     * attribute and true renders it without a value.
     */
    private function attributes(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $name => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            // guard against attribute-name injection
            if (!preg_match('/^[a-zA-Z_:][-a-zA-Z0-9_:.]*$/', (string)$name)) {
                continue;
            }

            $parts[] = $value === true ? $name : $name.'="'.e((string)$value).'"';
        }

        return implode(' ', $parts);
    }
}

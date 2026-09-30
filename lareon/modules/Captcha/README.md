# Captcha module

Local image captcha, GD only (no Intervention). Supports **many independent captchas on one page**.

## Requirements
PHP 8.2+, `ext-gd` (with FreeType), `ext-mbstring`, a cache store shared by all your servers (file / redis / database).
`APP_KEY` must be set (used to HMAC the answers).

## Show captchas

```blade
{{-- one captcha --}}
<form method="POST" action="/contact">
    @csrf
    @captchaField('default')
    <button>Send</button>
</form>

{{-- several forms on one page: each <form> gets its own captcha --}}
<form method="POST" action="/login">
    @csrf
    @captchaField('flat')
</form>

<form method="POST" action="/register">
    @csrf
    @captchaField('math')
</form>

{{-- several captchas inside ONE <form>: give them different names --}}
<form method="POST" action="/checkout">
    @csrf
    {!! captcha_field('mini', ['name' => 'captcha_a']) !!}
    {!! captcha_field('mini', ['name' => 'captcha_b']) !!}
</form>

@captchaScript {{-- once per page, prints the reload script only one time --}}
```

Each captcha renders: `<img>`, a reload button, `<input type=hidden name="{name}_token">` and `<input name="{name}">`.
Options of `captcha_field($preset, [...])`: `name`, `id`, `class`, `img[]`, `input[]`, `button[]`, `reload_label`.

## Validate

```php
use Lareon\Modules\Captcha\App\Rules\CaptchaRule;

// form 1  (login)
$request->validate([
    'captcha' => [new CaptchaRule('flat')],
]);

// form 2  (register)
$request->validate([
    'captcha' => [new CaptchaRule('math')],
]);

// two captchas in one request
$request->validate([
    'captcha_a' => [new CaptchaRule('mini')],
    'captcha_b' => [new CaptchaRule('mini')],
]);

// custom token field name
'human' => [new CaptchaRule('math', 'human_token')],
```

Passing the preset name to the rule is recommended: it stops a client from using a token
that was created with an easier preset.

Without the rule:

```php
captcha_check($request->captcha);                    // token read from "captcha_token"
captcha_check($request->input('a'), $request->input('a_token'), 'mini');
```

After a failed AJAX validation call `window.captchaReload(formElement)` to get fresh captchas
(a captcha is consumed on the first attempt, right or wrong).

## SPA / mobile API

```php
Route::get('/api/captcha', fn () => response()->json(captcha_make('custom', inline: true)));
// {"token": "...", "src": "...", "expires_in": 180, "img": "data:image/jpeg;base64,..."}

captcha_api_check($request->captcha, $request->captcha_token, 'custom');
```

## Presets
Defined in `config/config.php` under `presets`. Add your own, then use `captcha_field('my-preset')`.

## Security notes
- The answer is never stored or sent; only `HMAC-SHA256(app.key, token|answer)` is kept in cache with a TTL.
- One attempt per captcha (consumed even when wrong) -> no brute force on a single captcha.
- Token is 160 random bits; the image route only accepts `[a-f0-9]{40}`.
- Presets are whitelisted; unknown config keys are ignored and numbers are clamped.
- Creating captchas is rate limited by the `captcha` limiter (`throttle` in config).
- Add `throttle` middleware to the routes that *submit* your forms (login, register...), the captcha does not replace it.
- With the `database` cache driver the "consume once" step is not atomic; prefer redis or file for strict single use.

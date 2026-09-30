<?php

return [
    'name'       => 'Captcha',

    // Turn the captcha off completely (check() always passes, fields render nothing).
    // Intended for local development / automated tests only - never enable in production.
    'disable'    => env('CAPTCHA_DISABLE', false),

    // Cache store used for pending captchas. null = the default store.
    // Use a shared store (redis / database / file) when you run more than one server; "array" only works in tests.
    'store'      => env('CAPTCHA_CACHE_STORE'),

    // How many new captchas ("new code" button) one IP may request per minute.
    'throttle'   => 60,

    // Public URLs (relative to the site root). {token} is replaced with the captcha token.
    'routes'     => [
        'image'  => '/ajax/client-submitting/captcha/{token}',
        'reload' => '/ajax/client-submitting/captcha/load',
    ],

    // Alphabet of text captchas (visually confusing characters are left out on purpose).
    'characters' => ['2', '3', '4', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z'],

    /*
    | Presets. Use them with captcha_field('flat'), Captcha::make('flat') or new CaptchaRule('flat').
    |
    | Available keys (all optional):
    | length, width, height, quality, math, sensitive, expire (seconds), angle, lines, lineWidth,
    | lineColor, noise, bgImage, bgColor, fill, fontColors, contrast, sharpen, blur, invert,
    | marginTop, textLeftPadding, characters
    */
    'presets'    => [
        'default' => [
            'length'  => 5,
            'width'   => 120,
            'height'  => 36,
            'quality' => 90,
            'math'    => false,
            'expire'  => 180,
        ],
        'math'    => [
            'width'   => 120,
            'height'  => 36,
            'quality' => 90,
            'math'    => true,
        ],
        'flat'    => [
            'length'     => 6,
            'width'      => 160,
            'height'     => 46,
            'quality'    => 90,
            'lines'      => 6,
            'bgImage'    => false,
            'bgColor'    => '#ecf2f4',
            'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
            'contrast'   => -5,
        ],
        'mini'    => [
            'length' => 3,
            'width'  => 60,
            'height' => 32,
        ],
        'inverse' => [
            'length'    => 5,
            'width'     => 120,
            'height'    => 36,
            'quality'   => 90,
            'sensitive' => true,
            'angle'     => 12,
            'sharpen'   => 10,
            'blur'      => 2,
            'invert'    => true,
            'contrast'  => -5,
        ],
        'custom'  => [
            'length'     => 5,
            'width'      => 120,
            'height'     => 36,
            'quality'    => 90,
            'math'       => false,
            'expire'     => 180,
            'characters' => ['2', '3'],
            'fontColors' => ['#3f0211', '#3e023f', '#02083f', '#023f13', '#6c7c00', '#5b1f04'],
        ],
    ],
];

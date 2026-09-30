<?php

namespace Lareon\Modules\Captcha\App\Http\Controllers\Web\Admin\Captch;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Captcha\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller implements HasMiddleware
{
    public function __construct()
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.setting.read'),
        ];
    }

    public function index()
    {
        return view('captcha::admin.pages.captcha.index');
    }
}

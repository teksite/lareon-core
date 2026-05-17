<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Appearance;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;

class IconsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('can:admin'),
        ];
    }
    public function index()
    {
        return view('lareon::admin.pages.appearance.icons.index');

    }
}

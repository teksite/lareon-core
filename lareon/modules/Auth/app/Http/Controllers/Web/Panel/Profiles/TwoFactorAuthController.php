<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Panel\Profiles;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;


class TwoFactorAuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:panel.profile.towfactor'),
        ];
    }
    public function index()
    {
        $user = auth()->user();
        return view('lareon::panel.pages.profiles.two-factor', compact('user'));
    }

}

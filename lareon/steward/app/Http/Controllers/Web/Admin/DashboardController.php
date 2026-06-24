<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin'),
        ];
    }
    public function index()
    {
        return view("lareon::admin.pages.dashboard");
    }
}

<?php

namespace Lareon\Modules\Meta\App\Http\Controllers\Ajax\Admin\Models;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Meta\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModelsLoaderController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            new Middleware('can:admin'),
        ];
    }

    public function load(Request $request) {
        dd($request);
    }
}

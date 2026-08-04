<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Logics\RedirectLogic;

class RedirectsController extends Controller implements HasMiddleware
{

    public function __construct(public RedirectLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin.seo.redirect.edit', only: ['edit', 'update']),
        ];
    }

    public function edit()
    {

    }

    public function update()
    {

    }
}

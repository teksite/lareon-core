<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Logics\SeoSiteLogic;

class SeoSiteController extends Controller implements HasMiddleware
{

    public function __construct(public SeoSiteLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.seo.site.edit', only: ['edit', 'update']),
        ];
    }

    public function edit()
    {

    }

    public function update()
    {

    }
}

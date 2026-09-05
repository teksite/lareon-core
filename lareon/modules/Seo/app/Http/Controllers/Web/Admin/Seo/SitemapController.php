<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Logics\SitemapLogic;

class SitemapController extends Controller implements HasMiddleware
{

    public function __construct(public SitemapLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.seo.sitemap.edit', only: ['edit', 'update']),
        ];
    }

    public function index()
    {
        return view('seo::admin.pages.sitemap.index' );

    }

    public function generate()
    {

    }

    public function scan()
    {

    }
}

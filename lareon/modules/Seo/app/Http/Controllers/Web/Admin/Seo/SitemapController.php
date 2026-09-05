<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Logics\SitemapLogic;
use Teksite\Handler\Facade\Responder;

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
        $files = $this->logic->getFiles()->result;

        return view('seo::admin.pages.sitemap.index' ,compact('files'));

    }

    public function generate() {
        $res = $this->logic->generate();
        return Responder::fromResult($res,
            trans('lareon::global.crud.success.created', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.created', ['attribute' => __('page')]),
        )->go();
    }

    public function scan() {}
}

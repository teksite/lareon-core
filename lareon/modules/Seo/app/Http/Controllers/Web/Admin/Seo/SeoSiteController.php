<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Http\Requests\Admin\UpdateSeoSiteRequest;
use Lareon\Modules\Seo\App\Logics\SeoSiteLogic;
use Teksite\Handler\Facade\Responder;

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
        $items = $this->logic->all()->result;
        $website  = $items['website'];
        $localBusiness  = $items['localBusiness'] ?? [];
        $organization  = $items['organization'] ?? [];

        return view('seo::admin.pages.site.edit', compact('website' ,'localBusiness', 'organization'));
    }

    public function update(UpdateSeoSiteRequest $request)
    {
        $res = $this->logic->updateAll($request->validated('seo'));
        return Responder::fromResult($res)->go();
    }
}

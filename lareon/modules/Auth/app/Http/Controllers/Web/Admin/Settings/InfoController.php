<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Settings;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Logic\AppInfoLogic;

class InfoController extends Controller implements HasMiddleware
{

    public function __construct(public AppInfoLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.info.read'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=$this->logic->get();
        return view('lareon::admin.pages.settings.info.index', compact('data'));
    }

}

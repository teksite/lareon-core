<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Logic\CacheLogic;
use Teksite\Lareon\Facade\WebResponse;

class CachesController extends Controller implements HasMiddleware
{

    public function __construct(public CacheLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.cache.read'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res=$this->logic->get();
        $caches=$res->result;
        return view('lareon::admin.pages.settings.caches.index', compact('caches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function run(Request $request)
    {
       $validated= $request->validate([
            'command'=>'string|required',
        ]);
        $result = $this->logic->runCommand($validated['command']);
        return WebResponse::byResult($result)->go();
    }

}

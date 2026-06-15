<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;
use Lareon\Steward\App\Http\Controllers\Controller;
use Lareon\Steward\App\Http\Requests\Admin\CacheExecutionRequest;
use Lareon\Steward\App\Http\Requests\Admin\ClearLogRequest;
use Lareon\Steward\App\Logics\LogLogic;
use Teksite\Handler\Facade\Responder;

class LogsController extends Controller implements HasMiddleware
{
    public function __construct(public LogLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.log.read'),
            new Middleware('can:admin.setting.log.clear', only: ['clear']),
        ];
    }

    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function index()
    {
        $logs = $this->logic->getLogFiles()->result;
        $content = $this->logic->getLogContent()->result;
        $name = request()->input('name', 'laravel');

        return view('lareon::admin.pages.settings.log.index',  compact('logs', 'content', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function clear(ClearLogRequest $request)
    {
        $res = $this->logic->clearContent($request->input('name'));

        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.general'))->route('admin.settings.cache.index')->go()
            : Responder::failed(trans('lareon::global.crud.error.general'))->route('admin.settings.cache.index')->go();

    }

    /**
     * Store a newly created resource in storage.
     */

    public function destroy(ClearLogRequest $request)
    {
        $res = $this->logic->delete($request->input('name'));
        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.general'))->route('admin.settings.cache.index')->go()
            : Responder::failed(trans('lareon::global.crud.error.general'))->route('admin.settings.cache.index')->go();
    }
}

<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Steward\App\Enums\CacheAction;
use Lareon\Steward\App\Enums\CacheType;
use Lareon\Steward\App\Http\Controllers\Controller;
use Lareon\Steward\App\Http\Requests\Admin\CacheRequest;
use Lareon\Steward\App\Service\CacheManagerService;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;
use Teksite\SystemInfo\Repo\DatabaseInfo;
use Teksite\SystemInfo\Repo\LaravelInfo;
use Teksite\SystemInfo\Repo\PhpInfo;
use Teksite\SystemInfo\Support\DriverResolver;

class CacheController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.cache.read'),
            new Middleware('can:admin.setting.cache.create', only: ['store']),
            new Middleware('can:admin.setting.cache.delete', only: ['destroy']),
        ];
    }

    public function index()
    {


        return view('lareon::admin.pages.settings.cache.index', compact(''));
    }

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function execute(CacheRequest $request)
    {
        $res = ServiceWrapper::make(hasTransaction: false)
                             ->do(fn($request) => (new CacheManagerService())->run(CacheType::from($request->type), CacheAction::from($request->action)))
                             ->run();
        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.general'))->route('admin.setting.cache.index')->go()
            : Responder::failed(trans('lareon::global.crud.error.general'))->route('admin.setting.cache.index')->go();

    }

}

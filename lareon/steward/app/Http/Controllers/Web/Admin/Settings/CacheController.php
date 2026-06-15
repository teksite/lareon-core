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
        return view('lareon::admin.pages.settings.cache.index',  ['cacheTypes' => CacheType::cases()]);
    }

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function execute(CacheRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $type = $validated['type'];
        $res = ServiceWrapper::make(hasTransaction: false)
                             ->do(fn() => (new CacheManagerService())->run(CacheType::from($type), CacheAction::from($action)))
                             ->run();
        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.general'))->route('admin.settings.cache.index')->go()
            : Responder::failed(trans('lareon::global.crud.error.general'))->route('admin.settings.cache.index')->go();

    }

}

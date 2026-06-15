<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Lareon\Steward\App\Enums\CacheAction;
use Lareon\Steward\App\Enums\CacheType;
use Lareon\Steward\App\Http\Controllers\Controller;
use Lareon\Steward\App\Http\Requests\Admin\CacheExecutionRequest;
use Lareon\Steward\App\Service\CacheManagerService;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;
use Teksite\SystemInfo\Repo\DatabaseInfo;
use Teksite\SystemInfo\Repo\LaravelInfo;
use Teksite\SystemInfo\Repo\PhpInfo;
use Teksite\SystemInfo\Support\DriverResolver;

class LogsController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.log.read'),
            new Middleware('can:admin.setting.log.clear', only: ['clear']),
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
    public function clear(CacheExecutionRequest $request)
    {

        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.general'))->route('admin.settings.cache.index')->go()
            : Responder::failed(trans('lareon::global.crud.error.general'))->route('admin.settings.cache.index')->go();

    }

    private function getLogFile()
    {
        $files = File::files(storage_path('logs'));
        $files = array_map(function ($file) {
            return $file->getRealPath();
        }, $files);
        dd($files);
    }

}

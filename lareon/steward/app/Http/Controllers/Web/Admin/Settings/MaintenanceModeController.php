<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Lareon\Steward\App\Enums\CacheAction;
use Lareon\Steward\App\Enums\CacheType;
use Lareon\Steward\App\Http\Controllers\Controller;
use Lareon\Steward\App\Http\Requests\Admin\CacheExecutionRequest;
use Lareon\Steward\App\Service\CacheManagerService;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;

class MaintenanceModeController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.maintenance.edit'),
        ];
    }

    public function edit()
    {
        $isDown= App::isDownForMaintenance();
        return view('lareon::admin.pages.settings.maintenance.edit' , compact('isDown'));
    }

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function update(CacheExecutionRequest $request)
    {

    }

}

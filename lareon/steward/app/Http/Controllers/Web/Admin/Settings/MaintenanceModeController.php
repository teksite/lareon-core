<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Lareon\Steward\App\Http\Controllers\Controller;
use Lareon\Steward\App\Http\Requests\Admin\CacheExecutionRequest;
use Lareon\Steward\App\Http\Requests\Admin\MaintenanceModeRequest;
use Lareon\Steward\App\Logics\MaintenanceLogic;
use Teksite\Handler\Facade\Responder;

class MaintenanceModeController extends Controller implements HasMiddleware
{
    public function __construct(private MaintenanceLogic $logic) {}

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
    public function update(MaintenanceModeRequest $request)
    {
        $secret = $request->input('secret' , null);
        $res= $secret ? $this->logic->down($secret) :$this->logic->up();
        return  Responder::fromResult($res)->go();
    }

}

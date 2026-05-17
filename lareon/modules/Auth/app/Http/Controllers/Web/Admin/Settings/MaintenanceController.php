<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Http\Requests\Admin\UpdateMaintenanceModeRequest;
use Lareon\CMS\App\Logic\MaintenanceLogic;
use Teksite\Lareon\Facade\WebResponse;

class MaintenanceController extends Controller implements HasMiddleware
{
    public function __construct(public MaintenanceLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.edit'),
        ];
    }
    public function show()
    {
        $isDown= App::isDownForMaintenance();
        return view('lareon::admin.pages.settings.maintenance.show', compact('isDown'));
    }

    public function update(UpdateMaintenanceModeRequest $request)
    {
        $secret = $request->input('secret');

        if ($secret) {
            $res=$this->logic->down($secret);
        } else {
            $res=$this->logic->up();
        }
        return WebResponse::byResult($res)->go();
    }
}

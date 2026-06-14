<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Admin\Settings;


use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Steward\App\Http\Controllers\Controller;
use Teksite\SystemInfo\Repo\DatabaseInfo;
use Teksite\SystemInfo\Repo\LaravelInfo;
use Teksite\SystemInfo\Repo\PhpInfo;
use Teksite\SystemInfo\Support\DriverResolver;

class SystemInformationController extends Controller implements HasMiddleware
{
    public function __construct() {}


    public static function middleware()
    {
        return [
            new Middleware('can:admin.setting.info.read'),
        ];
    }

    public function index()
    {
        $phpInfo = (new PhpInfo())->collect();
        $laravelInfo = (new LaravelInfo())->collect();
        $databaseInfo = (new DatabaseInfo())->collector();
        $sysInfo = DriverResolver::driver()->collector();

        return view('lareon::admin.pages.settings.information.index', compact('phpInfo', 'laravelInfo', 'databaseInfo', 'sysInfo'));
    }
}

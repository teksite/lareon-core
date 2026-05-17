<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Lareon\CMS\App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function show()
    {
        $views=$this->loadComponents();
        return view('lareon::admin.pages.dashboard',compact('views'));
    }

    private function loadComponents(){
        $modules = \Teksite\Module\Facade\Module::enables();
        $views = [];
        $topViews = [];

        $cmsPath = base_path("Lareon/CMS/resources/views/admin/layouts/dashboard");
        foreach (glob($cmsPath . '/*.blade.php') as $file) {
            $viewName = pathinfo($file, PATHINFO_FILENAME);
            $viewName = str_replace('.blade','',$viewName);
            $fullView = "lareon::admin.layouts.dashboard.{$viewName}";

            if (str_starts_with($viewName, 'top-')) {
                $topViews[] = $fullView;
            } else {
                $views[] = $fullView;
            }
        }

        foreach($modules as $module){
            $path = base_path("Lareon/Modules/{$module}/resources/views/admin/layouts/dashboard");

            if (is_dir($path)) {
                $module = strtolower($module);

                foreach (glob($path . '/*.blade.php') as $file) {
                    $viewName = pathinfo($file, PATHINFO_FILENAME);
                    $viewName = str_replace('.blade','',$viewName);
                    $fullView = "{$module}::admin.layouts.dashboard.{$viewName}";

                    if (str_starts_with($viewName, 'top-')) {
                        $topViews[] = $fullView;
                    } else {
                        $views[] = $fullView;
                    }
                }
            }
        }

        // اول فایل‌های top- سپس بقیه
        return array_merge($topViews, $views);
    }

}

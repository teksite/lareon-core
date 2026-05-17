<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Panel;

use Illuminate\Http\Request;
use Lareon\CMS\App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function show()
    {
        $views=$this->loadComponents();

        return view('lareon::panel.pages.dashboard' ,compact('views'));
    }

    private function loadComponents(){
        $modules = \Teksite\Module\Facade\Module::enables();
        $views = [];
        $topViews = [];
        $cmsPath = base_path("Lareon/CMS/resources/views/panel/layouts/dashboard");
        foreach (glob($cmsPath . '/*.blade.php') as $file) {
            $viewName = pathinfo($file, PATHINFO_FILENAME);
            $viewName = str_replace('.blade','',$viewName);
            $fullView = "lareon::panel.layouts.dashboard.{$viewName}";

            if (str_starts_with($viewName, 'top-')) {
                $topViews[] = $fullView;
            } else {
                $views[] = $fullView;
            }
        }

        foreach($modules as $module){
            $module = strtolower($module);
            $path = base_path("Lareon/Modules/{$module}/resources/views/panel/layouts/dashboard");

            if (is_dir($path)) {
                foreach (glob($path . '/*.blade.php') as $file) {
                    $viewName = pathinfo($file, PATHINFO_FILENAME);
                    $viewName = str_replace('.blade','',$viewName);
                    $fullView = "{$module}::panel.layouts.dashboard.{$viewName}";

                    if (str_starts_with($viewName, 'top-')) {
                        $topViews[] = $fullView;
                    } else {
                        $views[] = $fullView;
                    }
                }
            }
        }

        return array_merge($topViews, $views);
    }

}

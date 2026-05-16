<?php

namespace Lareon\Steward\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Lareon\Steward\App\Service\MenuDiscoveryService;
use Lareon\Steward\App\Service\MenuService;
use Teksite\Module\Providers\Support\ModulesHeadquarterServiceProvider as ServiceProvider;


class ModulesHeadquarterServiceProvider extends ServiceProvider
{


    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        parent::boot();
        $this->loadViewComposers();
        $this->warmCache();
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();

        $this->app->singleton(MenuDiscoveryService::class);
        $this->app->singleton(MenuService::class);
        $this->app->alias(MenuService::class, 'menu');
    }

    protected function loadViewComposers(): void
    {
        view()->composer('lareon::admin.layouts.partials.aside', function ($view) {
            $view->with('menus', app(MenuService::class)->admin());
        });

        view()->composer('steward::user', function ($view) {
            $view->with('menus', app(MenuService::class)->panel());
        });
    }

    protected function warmCache(): void
    {
        $this->app->booted(function () {
            try {
                app(MenuService::class)->admin(true);
                app(MenuService::class)->panel(true);
            } catch (\Throwable) {
                // Silent fail
            }
        });
    }
}

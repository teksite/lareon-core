<?php

namespace Lareon\Steward\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Lareon\Steward\App\Service\MenuDiscoveryService;
use Lareon\Steward\App\Service\MenuService;
use Teksite\Module\Providers\Support\StewardServiceProvider as ServiceProvider;

class StewardServiceProvider extends ServiceProvider
{

    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = 'Lareon';

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = 'lareon';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [];

    /**
     * Define module schedules.
     */
    protected function configureSchedules(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // ...
    }

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RoutesHeadquarterServiceProvider::class,
        ModulesHeadquarterServiceProvider::class,
    ];


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
        view()->composer('steward::admin', function ($view) {
            $view->with('menus', app(MenuService::class)->adminTree());
        });

        view()->composer('steward::user', function ($view) {
            $view->with('menus', app(MenuService::class)->panelTree());
        });
    }

    protected function warmCache(): void
    {
        $this->app->booted(function () {
            try {
                app(MenuService::class)->adminTree(true);
                app(MenuService::class)->panelTree(true);
            } catch (\Throwable) {
                // Silent fail
            }
        });
    }
}

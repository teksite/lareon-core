<?php

namespace Lareon\Steward\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
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
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
    }



    /**
     * boot translations.
     */
    protected function bootTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->lowerModuleName);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->lowerModuleName);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $moduleLangPath = steward_path(config('modules.steward.lang_path', 'lang'));

            $this->loadTranslationsFrom($moduleLangPath ,$this->lowerModuleName);
            $this->loadJsonTranslationsFrom($moduleLangPath);
        }
    }
}

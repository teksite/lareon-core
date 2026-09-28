<?php

namespace Lareon\Modules\Gadget\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;

class GadgetServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "Gadget";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "gadget";

    /**
     * Module type (self|steward)
     *
     * @var string
     */
    protected string $type = "steward";


    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        //RouteServiceProvider::class,
    ];


    /**
     * Define module schedules.
     */
    protected function configureSchedules(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // ...
    }


    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        parent::boot();

        Blade::precompiler(function (string $string): string {
            return preg_replace_callback(
                '/<gadget::([a-zA-Z0-9._-]+)\s*\/>/',
                static function (array $matches): string {
                    $view = str_replace('.', '.', $matches[1]);
                    return <<<PHP
<?php echo view('gadgets.{$view}')->render(); ?>
PHP;
                },
                $string
            );
        });
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
    }
}

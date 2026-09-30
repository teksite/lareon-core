<?php

namespace Lareon\Modules\Captcha\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Str;
use Lareon\Modules\Captcha\App\Services\CaptchaService;
use Lareon\Modules\Captcha\App\Services\Facade\Captcha;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "Captcha";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "captcha";

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
        $this->bootCaptchaRules();
        $this->bootDirectives();
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
        $this->registerCaptcha();
    }

    public function bootCaptchaRules(): void
    {
        //        $validator = $this->app['validator'];
    }

    public function bootDirectives(): void
    {
        //        Blade::directive('captcha', function () {
        //            return view("captcha::components.load");
        //        });
    }


    protected function registerCaptcha(): void
    {
        // Register the facade alias: Captcha::create(), Captcha::check() ...
        AliasLoader::getInstance()->alias('Captcha', Captcha::class);

        $this->app->bind('captcha', function ($app) {
            return new CaptchaService(
                $app->make(Filesystem::class),
                $app->make(Repository::class), // Illuminate\Contracts\Config\Repository
                $app['session.store'],
                $app->make(BcryptHasher::class),
                $app->make(Str::class)
            );
        });
    }

}

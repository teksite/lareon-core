<?php

namespace Lareon\Modules\Captcha\App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Lareon\Modules\Captcha\App\Services\CaptchaRenderer;
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
        $this->bootRateLimiter();
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

    /**
     * Limit how many captcha one IP can create through the "new code" endpoint.
     */
    public function bootRateLimiter(): void
    {
        RateLimiter::for('captcha', function (Request $request) {
            return Limit::perMinute((int)$this->setting('throttle', 60))->by($request->ip());
        });
    }

    /**
     * Blade directives:
     * @captchaField('flat')  ->  captcha_field('flat')
     * @captchaScript         ->  captcha_script()
     * @captcha               ->  alias of @captchaField
     */
    public function bootDirectives(): void
    {
        Blade::directive('captchaField', fn($expression) => "<?php echo captcha_field({$expression}); ?>");
        Blade::directive('captcha', fn($expression) => "<?php echo captcha_field({$expression}); ?>");
        Blade::directive('captchaScript', fn() => '<?php echo captcha_script(); ?>');
    }

    protected function registerCaptcha(): void
    {
        AliasLoader::getInstance()->alias('Captcha', Captcha::class);

        $this->app->singleton('captcha', function ($app) {
            return new CaptchaService(
                $app['cache']->store($this->setting('store')),
                $app->make(Repository::class), // Illuminate\Contracts\Config\Repository
                new CaptchaRenderer(
                    module_path('Captcha', 'resources/assets/fonts'),
                    module_path('Captcha', 'resources/assets/backgrounds')
                )
            );
        });

        // allows type hinting CaptchaService in controllers / rules
        $this->app->alias('captcha', CaptchaService::class);
    }

    /**
     * Read a module setting from "modules.captcha.*" with fallback to "captcha.*".
     */
    private function setting(string $key, mixed $default = null): mixed
    {
        $config = $this->app->make(Repository::class);

        foreach (['modules.captcha.', 'captcha.'] as $prefix) {
            if ($config->has($prefix.$key)) {
                return $config->get($prefix.$key);
            }
        }

        return $default;
    }

}

<?php

namespace Lareon\Modules\Captcha\App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Config\Repository;
use Closure;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Lareon\Modules\Captcha\App\Services\CaptchaManager;
use Lareon\Modules\Captcha\App\Services\CaptchaRenderer;
use Lareon\Modules\Captcha\App\Services\CaptchaService;
use Lareon\Modules\Captcha\App\Services\Drivers\CloudflareTurnstileDriver;
use Lareon\Modules\Captcha\App\Services\Drivers\GoogleRecaptchaDriver;
use Psr\Log\LoggerInterface;
use Throwable;
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
     * Blade directives (all print the captcha of the driver set in config):
     *
     * @captcha                    default preset
     * @captcha('flat')            preset (local driver)
     * @captcha('default', ['name' => 'login_captcha'])
     * @captchaField(...)          same as @captcha
     * @captchaScript              only needed with ['script' => false]
     */
    public function bootDirectives(): void
    {
        Blade::directive('captcha', fn($expression) => "<?php echo captcha_field({$expression}); ?>");
        Blade::directive('captchaField', fn($expression) => "<?php echo captcha_field({$expression}); ?>");
        Blade::directive('captchaScript', fn($expression) => "<?php echo captcha_script({$expression}); ?>");
    }

    protected function registerCaptcha(): void
    {
        // Facade alias: Captcha::field(), Captcha::check() ...
        AliasLoader::getInstance()->alias('Captcha', Captcha::class);

        // The manager (and every driver) keeps no per-request state: one shared instance, Octane safe.
        $this->app->singleton('captcha', function ($app) {
            $manager = new CaptchaManager($app->make(Repository::class));

            $manager->extend('local', fn() => new CaptchaService(
                $app['cache']->store($this->setting('store')),
                $app->make(Repository::class),
                new CaptchaRenderer(
                    module_path('Captcha', 'resources/assets/fonts'),
                    module_path('Captcha', 'resources/assets/backgrounds')
                )
            ));

            $manager->extend('google', fn() => new GoogleRecaptchaDriver(
                (array)$this->setting('drivers.google', []),
                $this->httpPoster(),
                fn() => request()->ip(),
                $app->make(LoggerInterface::class)
            ));

            $manager->extend('cloudflare', fn() => new CloudflareTurnstileDriver(
                (array)$this->setting('drivers.cloudflare', []),
                $this->httpPoster(),
                fn() => request()->ip(),
                $app->make(LoggerInterface::class)
            ));

            return $manager;
        });

        $this->app->alias('captcha', CaptchaManager::class);

        // type hinting CaptchaService (controllers, SPA endpoints) always gives the built-in image captcha
        $this->app->singleton(CaptchaService::class, fn($app) => $app->make('captcha')->local());
    }

    /**
     * POSTs a form to a verification endpoint and returns the decoded JSON (null on any failure).
     *
     * @return Closure(string, array, int, ?string): ?array
     */
    private function httpPoster(): Closure
    {
        return function (string $url, array $form, int $timeout, ?string $proxy): ?array {
            try {
                $request = Http::asForm()
                               ->acceptJson()
                               ->connectTimeout(min($timeout, 5))
                               ->timeout($timeout);

                if ($proxy !== null) {
                    $request = $request->withOptions(['proxy' => $proxy]);
                }

                $response = $request->post($url, $form);
                $json = $response->successful() ? $response->json() : null;

                return is_array($json) ? $json : null;
            } catch (Throwable) {
                return null;
            }
        };
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

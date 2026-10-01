<?php

namespace Lareon\Modules\Auth\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Lareon\Modules\Auth\App\Models\PersonalAccessToken;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "Auth";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "auth";

    /**
     * Module type (self|steward)
     *
     * @var string
     */
    protected string $type = "steward";


    public const array OauthType = ['google', 'github', 'gitlab', 'linkedin', 'facebook', 'twitter'];
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
//        RouteServiceProvider::class,
        FortifyServiceProvider::class,
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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
        $this->setDefault();

    }


    private function setDefault(): void
    {
        foreach (self::OauthType as $type) {
            $upperCaseType = strtoupper($type);
            Config::set("services.{$type}", [
                'client_id' => env("{$upperCaseType}_CLIENT_ID"),
                'client_secret' => env("{$upperCaseType}_CLIENT_SECRET_KEY"),
                'redirect' =>env("{$upperCaseType}_CALLBACK_URL"),
            ]);
        }
    }
}

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
        Config::set('services.google', [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=google'),
        ]);
        Config::set('services.github', [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=github'),
        ]);

        Config::set('services.gitlab', [
            'client_id' => env('GITLAB_CLIENT_ID'),
            'client_secret' => env('GITLAB_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=gitlab'),
        ]);
        Config::set('services.linkedin', [
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=linkedin'),
        ]);
        Config::set('services.facebook', [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=facebook'),
        ]);
        Config::set('services.twitter', [
            'client_id' => env('TWITTER_CLIENT_ID'),
            'client_secret' => env('TWITTER_CLIENT_SECRET'),
            'redirect' =>url('auth/oauth/callback?type=twitter'),
        ]);
    }
}

<?php

namespace Lareon\Modules\Ticketing\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Policy\TicketPolicy;
use Lareon\Modules\User\App\Models\User;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;

class TicketingServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "Ticketing";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "ticketing";

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
        Gate::policy(Ticket::class, TicketPolicy::class);

        $this->loadDynamicRelations();
        $this->registerAttachmentDisk();
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
    }

    public function loadDynamicRelations(): void
    {
        User::resolveRelationUsing('tickets', fn(User $user) => $user->hasMany(Ticket::class));
    }


    private function registerAttachmentDisk(): void
    {
        $diskName = config('ticketing.attachments.disk', 'ticketing');
        if (config("filesystems.disks.{$diskName}") !== null) return;

        Config::set("filesystems.disks.{$diskName}", [
            'driver' => 'local',
            'root' => storage_path('app/private/ticketing'),
            'url' => env('APP_URL').'/storage/ticketing',
            'visibility' => 'private',
            'throw' => false,
        ]);
    }
}

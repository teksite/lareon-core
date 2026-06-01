<?php

namespace Lareon\Modules\Auth\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Lareon\Modules\Auth\App\Events\PermissionCrudEvent;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Authorize\Models\Role;

class NewPermissionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PermissionCrudEvent $event): void
    {
        $operation = $event->operation;
        $data = $event->operation;
        $permission = $event->permission;

        if ($operation === CrudTypeEnum::CREATE) {
            $roleIds = Role::query()
                           ->where('hierarchy', '<=', 5)
                           ->pluck('id');
            $permission->roles()->attach($roleIds);
        }
    }
}

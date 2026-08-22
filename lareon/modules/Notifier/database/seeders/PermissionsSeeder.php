<?php

namespace Lareon\Modules\Notifier\Database\Seeders;

use Illuminate\Database\Seeder;
use Teksite\Authorize\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::query()->insert([
            /*ADMIN*/

            [
                'title'=>'admin.notification.read',
                'description'=>'have access to read one or all notifications (in the admin panel)',
            ],
            [
                'title'=>'admin.notification.create',
                'description'=>'have access to create a new notification (in the admin panel)',
            ],
            [
                'title'=>'admin.notification.edit',
                'description'=>'have access to edit notifications (in the admin panel)',
            ],
            [
                'title'=>'admin.notification.delete',
                'description'=>'have access to delete notifications (in the admin panel)',
            ],


            /* Panel */
            [
                'title'=>'panel.notification.read',
                'description'=>'have access to read one or all related notifications (in the notification panel)',
            ],
            [
                'title'=>'panel.notification.create',
                'description'=>'have access to create a new notification (in the notification panel)',
            ],
            [
                'title'=>'panel.notification.edit',
                'description'=>'have access to edit related notifications (in the notification panel)',
            ],
            [
                'title'=>'panel.notification.delete',
                'description'=>'have access to delete related notifications (in the notification panel)',
            ],
        ]);

    }
}

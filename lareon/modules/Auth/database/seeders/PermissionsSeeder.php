<?php

namespace Lareon\Modules\Auth\Database\Seeders;

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
                'title'=>'admin',
                'description'=>'have access to admin panel (in the admin panel)',
            ],
            /* Roles */
            [
                'title'=>'admin.role.read',
                'description'=>'have access to read one or all roles (in the admin panel)',
            ],
            [
                'title'=>'admin.role.create',
                'description'=>'have access to create a new role (in the admin panel)',
            ],
            [
                'title'=>'admin.role.edit',
                'description'=>'have access to edit roles (in the admin panel)',
            ],
            [
                'title'=>'admin.role.delete',
                'description'=>'have access to delete roles (in the admin panel)',
            ],
            /* Permissions */
            [
                'title'=>'admin.permission.read',
                'description'=>'have access to read one or all permissions (in the admin panel)',
            ],
            [
                'title'=>'admin.permission.create',
                'description'=>'have access to create a new permission (in the admin panel)',
            ],
            [
                'title'=>'admin.permission.edit',
                'description'=>'have access to edit permissions (in the admin panel)',
            ],
            [
                'title'=>'admin.permission.delete',
                'description'=>'have access to delete permissions (in the admin panel)',
            ],
            /* Settings */
            [
                'title'=>'admin.setting.read',
                'description'=>'have access to setting parts and read them (in the admin panel)',
            ],
            [
                'title'=>'admin.setting.edit',
                'description'=>'have access to edit settings (in the admin panel)',
            ],
            /* Settings */
            [
                'title'=>'admin.setting.info.read',
                'description'=>'have access to information of the app (in the admin panel)',
            ],
            /* Cache */
            [
                'title'=>'admin.setting.cache.read',
                'description'=>'have access to the caches (in the admin panel)',
            ],
            [
                'title'=>'admin.setting.cache.create',
                'description'=>'have access to cache determined data (in the admin panel)',
            ],
            [
                'title'=>'admin.setting.cache.delete',
                'description'=>'have access to delete determined caches (in the admin panel)',
            ],
            /* Log */
            [
                'title'=>'admin.setting.log.read',
                'description'=>'have access to the logs (in the admin panel)',
            ],
            [
                'title'=>'admin.setting.log.clear',
                'description'=>'have access to clear log files (in the admin panel)',
            ],
            [
                'title'=>'admin.setting.log.delete',
                'description'=>'have access to delete log files (in the admin panel)',
            ],
            [
                'title'=>'admin.file.manager.edit',
                'description'=>'have access to manage file manager (in the admin panel)',
            ],
            [
                'title'=>'admin.info.read',
                'description'=>'have access to read general info of site (in the admin panel)',
            ],
            /* users */
            [
                'title'=>'admin.user.read',
                'description'=>'have access to read one or all users (in the admin panel)',
            ],
            [
                'title'=>'admin.user.create',
                'description'=>'have access to create a new user (in the admin panel)',
            ],
            [
                'title'=>'admin.user.edit',
                'description'=>'have access to edit users (in the admin panel)',
            ],
            [
                'title'=>'admin.user.delete',
                'description'=>'have access to delete users (in the admin panel)',
            ],
            [
                'title'=>'admin.user.acl.edit',
                'description'=>'have access to change role-permission of users (in the admin panel)',
            ],

            /*  PANEL   */
            [
                'title'=>'panel',
                'description'=>'have access to user panel',
            ],
            /* users */
            [
                'title'=>'panel.user.read',
                'description'=>'have access to read one or all related users (in the user panel)',
            ],
            [
                'title'=>'panel.user.create',
                'description'=>'have access to create a new user (in the user panel)',
            ],
            [
                'title'=>'panel.user.edit',
                'description'=>'have access to edit related users (in the user panel)',
            ],
            [
                'title'=>'panel.user.delete',
                'description'=>'have access to delete related users (in the user panel)',
            ],
            /* profile */
            [
                'title'=>'panel.profile.edit',
                'description'=>'have access to edit profile data (in the user panel)',
            ],
            [
                'title'=>'panel.profile.towfactor',
                'description'=>'have access to enable\disable two factor authentication (in the user panel)',
            ],
            [
                'title'=>'panel.profile.delete',
                'description'=>'have access to delete the account (in the user panel)',
            ],

        ]);

    }
}

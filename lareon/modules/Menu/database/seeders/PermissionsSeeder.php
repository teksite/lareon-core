<?php

namespace Lareon\Modules\Menu\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'title'       => 'admin.menu.read',
                'description' => 'have access to read one or all menu (in the admin panel)',
            ],
            [
                'title'       => 'admin.menu.create',
                'description' => 'have access to create a new menu (in the admin panel)',
            ],
            [
                'title'       => 'admin.menu.edit',
                'description' => 'have access to edit menu (in the admin panel)',
            ],
            [
                'title'       => 'admin.menu.delete',
                'description' => 'have access to delete menu (in the admin panel)',
            ],
            [
                'title'       => 'admin.menu.trash',
                'description' => 'have access to delete menu from database (in the admin panel)',
            ],
        ]);
    }
}

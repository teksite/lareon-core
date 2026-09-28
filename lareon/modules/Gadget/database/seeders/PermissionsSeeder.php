<?php

namespace Lareon\Modules\Gadget\Database\Seeders;

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

            /*ADMIN*/
            [
                'title' => 'admin.gadget.read',
                'description' => 'have access to read one or all gadgets (in the admin panel)',
            ],
            [
                'title' => 'admin.gadget.create',
                'description' => 'have access to create a new gadget (in the admin panel)',
            ],
            [
                'title' => 'admin.gadget.edit',
                'description' => 'have access to edit gadgets (in the admin panel)',
            ],
            [
                'title' => 'admin.gadget.delete',
                'description' => 'have access to delete gadgets (in the admin panel)',
            ],
            [
                'title' => 'admin.gadget.trash',
                'description' => 'have access to delete gadgets from database (in the admin panel)',
            ],
        ]);
    }
}

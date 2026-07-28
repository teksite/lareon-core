<?php

namespace Lareon\Modules\Meta\Database\Seeders;

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

            /* Elements */
            [
                'title'=>'admin.meta.element.read',
                'description'=>'have access to read one or all components for editors (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.element.create',
                'description'=>'have access to create a components for editors (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.element.edit',
                'description'=>'have access to edit components for editors (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.element.delete',
                'description'=>'have access to delete components for editors (in the admin panel)',
            ],


            /* Template */
            [
                'title'=>'admin.meta.template.read',
                'description'=>'have access to read one or all attached components to models (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.template.create',
                'description'=>'have access to attach components to model (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.template.edit',
                'description'=>'have access to edit attached components to model (in the admin panel)',
            ],
            [
                'title'=>'admin.meta.template.delete',
                'description'=>'have access to delete attached components to model (in the admin panel)',
            ],


        ]);

    }
}

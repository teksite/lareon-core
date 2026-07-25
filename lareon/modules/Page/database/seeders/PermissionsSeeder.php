<?php

namespace Lareon\Modules\Page\Database\Seeders;

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
                'title'       => 'admin.page.read',
                'description' => 'have access to read one or all pages (in the admin panel)',
            ],
            [
                'title'       => 'admin.page.create',
                'description' => 'have access to create a new page (in the admin panel)',
            ],
            [
                'title'       => 'admin.page.edit',
                'description' => 'have access to edit pages (in the admin panel)',
            ],
            [
                'title'       => 'admin.page.delete',
                'description' => 'have access to delete pages (in the admin panel)',
            ],

            [
                'title'       => 'admin.page.trash',
                'description' => 'have access to delete pages from database (in the admin panel)',
            ],

        ]);
    }
}

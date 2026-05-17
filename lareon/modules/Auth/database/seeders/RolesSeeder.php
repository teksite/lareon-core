<?php

namespace Lareon\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Teksite\Authorize\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->insert([
            [
                'title'=>'owner',
                'hierarchy'=>'0',
                'description'=>'a user who is owner of the app and access to all parts of the app',
            ],
            [
                'title'=>'administrator',
                'hierarchy'=>'1',
                'description'=>'a user can access to all parts of the app',
            ],
            [
                'title'=>'admin',
                'hierarchy'=>'2',
                'description'=>'users can access to all parts of the app but can not edit the owner and administrator user',
            ],
            [
                'title'=>'user',
                'hierarchy'=>'50',
                'description'=>'regular users that just make an account',
            ],
            [
                'title'=>'abandonment',
                'hierarchy'=>'100',
                'description'=>'accounts that are banned and have no accessibility',
            ]
        ]);

    }
}

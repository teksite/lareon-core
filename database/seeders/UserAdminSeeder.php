<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lareon\Steward\App\Models\Admin;
use Teksite\Authorize\Models\Role;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::query()->create([
            'name'     => 'sina Zangiband',
            'email'    => 'sina.zangiband@gmail.com',
            'password' => Hash::make('sina.zangiband@gmail.com'),

        ]);
        $ownerRole = Role::query()->firstWhere('title', 'owner');

        if ($ownerRole) $admin->roles()->sync($ownerRole->id);
    }
}

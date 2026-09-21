<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lareon\Modules\User\App\Models\User;
use Teksite\Authorize\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userZero = User::query()->create([
            'name'     => 'sina zb',
            'email'    => 'zb.sina@gmail.com',
            'password' => Hash::make('zb.sina@gmail.com'),
            'phone'    => '989382295515',
            'slug'     => '989382295515',

        ]);
        $userZero->markEmailAsVerified();
        $userZero->markPhoneAsVerified();


        $userRole = Role::query()->firstWhere('title', 'user');

        $userZero->roles()->attach($userRole->id);


        $users = User::factory(10)->create();

        foreach ($users as $newUser) {
            $newUser->roles()->attach($userRole->id);
        }
    }
}

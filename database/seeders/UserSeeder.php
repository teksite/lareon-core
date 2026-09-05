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
        $admin = $this->makeAdmin();


        $this->makeUsers($admin);
    }

    private function makeAdmin()
    {
        $user = User::query()->create([
            'name'     => 'sina',
            'lastname' => 'Zangiband',
            'email'    => 'sina.zangiband@gmail.com',
            'password' => Hash::make('sina.zangiband@gmail.com'),
            'phone'    => '989126037279',
            'slug'     => '989126037279',

        ]);
        $user->markEmailAsVerified();
        $user->markPhoneAsVerified();
        $ownerRole = Role::query()->firstWhere('title', 'owner');

        if ($ownerRole) $user->roles()->sync($ownerRole->id);

        return $user;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|User $admin
     * @return void
     */
    private function makeUsers(\Illuminate\Database\Eloquent\Model|User $admin): void
    {
        $userRole = Role::query()->firstWhere('title', 'user');

        $users = User::factory(45)->create([
            'parent_id' => $admin->id,
        ]);


        foreach ($users as $newUser) {
            $newUser->roles()->attach($userRole->id);
        }
    }
}

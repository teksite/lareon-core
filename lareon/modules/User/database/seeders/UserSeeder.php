<?php

namespace Lareon\Modules\User\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lareon\Modules\User\App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Sina Zangiband',
            'email' => 'sina.zangiband@gmail.com',
            'password' => Hash::make('sina.zangiband@gmail.com',),
        ]);
        $user = User::find(1);
        $user->markEmailAsVerified();
    }
}

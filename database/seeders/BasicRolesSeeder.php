<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Teksite\Authorize\Models\Permission;
use Teksite\Authorize\Models\Role;

class BasicRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $allPermissions = Permission::query()->select(['id' ,'title'])->get();

        foreach (Role::query()->whereIn('title' , ['owner', 'administrator', 'admin',])->get() as $role) {
            $role->permissions()->sync($allPermissions->pluck('id')->toArray());
        }

        foreach (Role::query()->whereIn('title' , ['user'])->get() as $role) {
            $role->permissions()->sync($allPermissions->where('title' ,"LIKE" ,"panel%")->pluck('id')->toArray());
        }
    }
}

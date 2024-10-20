<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::query()->create([
            'title' => 'admin',
        ]);

        $permissions = Permission::all();

        $admin->permissions()->attach($permissions);


        Role::query()->create([
            'title' => 'user',
        ]);
    }
}

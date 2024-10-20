<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * categories Permissions
         */
        Permission::query()->insert([

            [
              'title' => 'create-categories'
            ],

            [
                'title' => 'read-categories'
            ],

            [
                'title' => 'update-categories'
            ],

            [
                'title' => 'delete-categories'
            ]
        ]);

        /**
         * artist permissions
         */
        Permission::query()->insert([

            [
                'title' => 'create-artist'
            ],

            [
                'title' => 'read-artist'
            ],

            [
                'title' => 'update-artist'
            ],

            [
                'title' => 'delete-artist'
            ]
        ]);

        /**
         * users permissions
         */
        Permission::query()->insert([

            [
                'title' => 'read-users'
            ],

            [
                'title' => 'update-users'
            ],

            [
                'title' => 'delete-users'
            ]
        ]);

        /**
         * roles permissions
         */
        Permission::query()->insert([

            [
                'title' => 'create-roles'
            ],

            [
                'title' => 'read-roles'
            ],

            [
                'title' => 'update-roles'
            ],

            [
                'title' => 'delete-roles'
            ]
        ]);
    }
}

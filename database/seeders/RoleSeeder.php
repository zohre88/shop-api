<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
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
       
        

        /**
         * super-admin roles
         */

        $superAdmin=Role::query()->create([
            'title' => 'super-admin'
        ]);

        $superAdmin->permissions()->attach(Permission::all());

         /**
         * normal-user
         */
        Role::query()->insert([
            'title' => 'normal-user'
        ]);
    }
}

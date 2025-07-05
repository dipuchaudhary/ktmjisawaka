<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'mulldarta-list',
           'mulldarta-create',
           'mulldarta-edit',
           'mulldarta-delete',
           'bankingdarta-list',
           'bankingdarta-create',
           'bankingdarta-edit',
           'bankingdarta-delete',
           'patrachallani-list',
           'patrachallani-create',
           'patrachallani-edit',
           'patrachallani-delete',
           'aviyog-list',
           'aviyog-create',
           'aviyog-edit',
           'aviyog-delete',
           'punarabedan-list',
           'punarabedan-create',
           'punarabedan-edit',
           'punarabedan-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',

        ];



        foreach ($permissions as $permission) {

             Permission::create(['name' => $permission]);

        }
    }
}

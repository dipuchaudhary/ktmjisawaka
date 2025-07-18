<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'aviyog'],
            ['name' => 'chalani'],
            ['name' => 'banking'],
            ['name' => 'punarabedan'],
            ['name' => 'darta'],
            ['name' => 'faat'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

    }
}

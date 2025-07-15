<?php

namespace Database\Seeders;
use DB;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $adminuser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('admin@123')
        ]);

        $users = [
            ['name' => 'सचिवालय', 'email' => 'sachiwalaya@dgao.com', 'password' => bcrypt('sachiwalaya@123')],
            ['name' => 'प्रशासन', 'email' => 'prasasan@dgao.com', 'password' => bcrypt('prasasan@123')],
            ['name' => 'लेखा', 'email' => 'lekha@dgao.com', 'password' => bcrypt('lekha@123')],
            ['name' => 'जिन्सी', 'email' => 'jinsi@dgao.com', 'password' => bcrypt('jinsi@123')],
            ['name' => 'बैकिङ्ग', 'email' => 'banking@dgao.com', 'password' => bcrypt('banking@123')],
            ['name' => 'फाँट क', 'email' => 'faatka@dgao.com', 'password' => bcrypt('faatka@123')],
            ['name' => 'फाँट ख', 'email' => 'faatkha@dgao.com', 'password' => bcrypt('faatkha@123')],
            ['name' => 'दर्ता', 'email' => 'darta@dgao.com', 'password' => bcrypt('darta@123')],
            ['name' => 'पुनरावेदन', 'email' => 'punarabedan@dgao.com', 'password' => bcrypt('punarabedan@123')],
            ['name' => 'अभियोग', 'email' => 'aviyog@dgao.com', 'password' => bcrypt('aviyog@123')],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $role = Role::create(['name' => 'SuperAdmin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $adminuser->assignRole($role);
    }
}

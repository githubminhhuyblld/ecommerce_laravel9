<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_list = Permission::create(['name' => 'users.list']);
        $user_view = Permission::create(['name' => 'users.view']);
        $user_create = Permission::create(['name' => 'users.create']);
        $user_update = Permission::create(['name' => 'users.update']);
        $user_delete = Permission::create(['name' => 'users.delete']);

        $admin_Role = Role::create(['name' => 'admin']);
        $admin_Role->givePermissionTo([
            $user_list,
            $user_view,
            $user_update,
            $user_create,
            $user_delete
        ]);
        $admin = User::create([
            'name' => 'Nguyễn Minh Huy',
            'email' => 'minhhuy122001@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $admin -> assignRole($admin_Role);
        $admin -> givePermissionTo([
            $user_list,
            $user_view,
            $user_update,
            $user_create,
            $user_delete
        ]);

        $user = User::create([
            'name' => 'Hồ Quang Hiếu',
            'email' => 'hieu@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user_Role = Role::create(['name' => 'user']);
        $user_Role->givePermissionTo([
            $user_list,
        ]);
        $user -> assignRole($user_Role);
        $user -> givePermissionTo([
            $user_list
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'staff', 'customer'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // contoh user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@cafelora.test'],
            ['name'=>'Admin','password'=>bcrypt('password')]
        );
        $admin->assignRole('admin');

        $staff = User::firstOrCreate(
            ['email'=>'staff@cafelora.test'],
            ['name'=>'Staff','password'=>bcrypt('password')]
        );
        $staff->assignRole('staff');

        $customer = User::firstOrCreate(
            ['email'=>'customer@cafelora.test'],
            ['name'=>'Customer','password'=>bcrypt('password')]
        );
        $customer->assignRole('customer');
    }
}

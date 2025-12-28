<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        $this->call(class: [
            RoleSeeder::class,
        ]);

        // Generate 5 user admin faker
        User::factory(5)->make()->each(function ($user) {
            $user->email = Str::before($user->email, '@') . '@cafelora.my.id';
            $user->password = bcrypt('pw@admin');
            $user->save();
            $user->assignRole('admin');
        });

        // Generate 5 user staff faker
        User::factory(5)->make()->each(function ($user) {
            $user->email = Str::before($user->email, '@') . '@cafelora.my.id';
            $user->password = bcrypt('pw@staff');
            $user->save();
            $user->assignRole('staff');
        });

        // Admin tetap
        User::factory()->create([
            'name' => 'Admin Cafelora',
            'email' => 'admin@cafelora.my.id',
            'password' => bcrypt('pw@admin'),
        ])->assignRole('admin');

        // Staff tetap
        User::factory()->create([
            'name' => 'Staff Cafelora',
            'email' => 'staff@cafelora.my.id',
            'password' => bcrypt('pw@staff'),
        ])->assignRole('staff');

        // Seed menu system
        $this->call([
            MenuSystemSeeder::class,
        ]);

    }
}

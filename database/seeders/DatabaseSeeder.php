<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Variant;

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
            $user->password = bcrypt('pw@admin');
            $user->save();
            $user->assignRole('admin');
        });

        // Generate 5 user staff faker
        User::factory(5)->make()->each(function ($user) {
            $user->password = bcrypt('pw@staff');
            $user->save();
            $user->assignRole('staff');
        });

        // Admin tetap
        User::factory()->create([
            'name' => 'Admin Cafelora',
            'email' => 'admin@cafelora.test',
            'password' => bcrypt('pw@admin'),
        ])->assignRole('admin');

        // Staff tetap
        User::factory()->create([
            'name' => 'Staff Cafelora',
            'email' => 'staff@cafelora.test',
            'password' => bcrypt('pw@staff'),
        ])->assignRole('staff');

        // Seed menu system
        $this->call([
            MenuSystemSeeder::class,
        ]);

        // Seed variants
        Variant::insert([
            ['name' => 'Regular', 'price_adjustment' => 0],
            ['name' => 'Large', 'price_adjustment' => 5000],
            ['name' => 'Hot', 'price_adjustment' => 0],
            ['name' => 'Ice', 'price_adjustment' => 3000],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first (required for users)
        $this->call([
            RoleSeeder::class,
        ]);

        // Create test users with different roles
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $studentRole = \App\Models\Role::where('name', 'student')->first();

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'role_id' => $adminRole->id,
        ]);

        // Create student user
        User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'phone' => '+1234567892',
            'role_id' => $studentRole->id,
        ]);

        // Create additional random users
        User::factory(10)->create();
    }
}

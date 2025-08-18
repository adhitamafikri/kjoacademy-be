<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create system roles (essential for LMS)
        $systemRoles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full system access and control',
                'permissions' => [
                    'courses.view',
                    'courses.create',
                    'courses.edit',
                    'courses.delete',
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'enrollments.view',
                    'enrollments.create',
                    'enrollments.edit',
                    'enrollments.delete',
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',
                    'reports.view',
                    'reports.create',
                    'settings.view',
                    'settings.edit',
                    'roles.view',
                    'roles.create',
                    'roles.edit',
                    'roles.delete',
                ],
            ],
            [
                'name' => 'student',
                'description' => 'Student with access to enroll and learn from courses',
                'permissions' => [
                    'courses.view',
                    'enrollments.view',
                    'enrollments.create',
                ],
            ],
        ];

        // Create system roles
        foreach ($systemRoles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Roles seeded successfully!');
        $this->command->info('Created roles: ' . implode(', ', Role::pluck('name')->toArray()));
    }
}

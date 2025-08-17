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
            [
                'name' => 'moderator',
                'description' => 'Content moderator with limited administrative capabilities',
                'permissions' => [
                    'courses.view',
                    'courses.edit',
                    'enrollments.view',
                    'enrollments.edit',
                    'categories.view',
                    'categories.edit',
                    'reports.view',
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

        // Create additional custom roles for testing
        $customRoles = [
            [
                'name' => 'content_creator',
                'description' => 'Specialized role for creating and managing course content',
                'permissions' => [
                    'courses.view',
                    'courses.create',
                    'courses.edit',
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                ],
            ],
            [
                'name' => 'support_agent',
                'description' => 'Customer support agent with limited access',
                'permissions' => [
                    'users.view',
                    'enrollments.view',
                    'enrollments.edit',
                    'reports.view',
                ],
            ],
            [
                'name' => 'analyst',
                'description' => 'Data analyst with reporting capabilities',
                'permissions' => [
                    'courses.view',
                    'enrollments.view',
                    'reports.view',
                    'reports.create',
                ],
            ],
        ];

        // Create custom roles
        foreach ($customRoles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Create some random roles for testing (optional)
        if (app()->environment('local', 'testing')) {
            Role::factory(5)->create();
        }

        $this->command->info('Roles seeded successfully!');
        $this->command->info('Created roles: ' . implode(', ', Role::pluck('name')->toArray()));
    }
}

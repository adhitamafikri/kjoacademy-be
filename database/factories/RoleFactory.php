<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'permissions' => fake()->randomElements([
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
                'settings.view',
                'settings.edit',
            ], fake()->numberBetween(1, 5)),
        ];
    }

    /**
     * Indicate that the role is for admin users.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'admin',
            'description' => 'Administrator with full system access',
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
                'settings.view',
                'settings.edit',
            ],
        ]);
    }

    /**
     * Indicate that the role is for students.
     */
    public function student(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'student',
            'description' => 'Student with course access',
            'permissions' => [
                'courses.view',
                'enrollments.view',
            ],
        ]);
    }

    /**
     * Indicate that the role has minimal permissions.
     */
    public function limited(): static
    {
        return $this->state(fn(array $attributes) => [
            'permissions' => fake()->randomElements([
                'courses.view',
                'enrollments.view',
            ], fake()->numberBetween(1, 2)),
        ]);
    }

    /**
     * Indicate that the role has extensive permissions.
     */
    public function extensive(): static
    {
        return $this->state(fn(array $attributes) => [
            'permissions' => fake()->randomElements([
                'courses.view',
                'courses.create',
                'courses.edit',
                'courses.delete',
                'users.view',
                'users.create',
                'users.edit',
                'enrollments.view',
                'enrollments.create',
                'enrollments.edit',
                'categories.view',
                'categories.create',
                'categories.edit',
                'reports.view',
            ], fake()->numberBetween(8, 12)),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'category_id' => CourseCategory::factory(),
            'instructor_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'thumbnail_url' => fake()->imageUrl(640, 480, 'education'),
            'enrollment_count' => fake()->numberBetween(0, 1000),
            'is_published' => fake()->boolean(80), // 80% published
            'price' => fake()->randomFloat(2, 0, 299.99),
            'duration_minutes' => fake()->numberBetween(30, 480),
            'difficulty_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'metadata' => [
                'tags' => fake()->words(3),
                'language' => 'English',
                'certificate' => fake()->boolean(),
            ],
        ];
    }

    public function published()
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function free()
    {
        return $this->state(fn(array $attributes) => [
            'price' => 0.00,
        ]);
    }
}

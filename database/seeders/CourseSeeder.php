<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Get all categories
        $categories = CourseCategory::all()->keyBy('slug');

        // Create sample courses with realistic category assignments
        $courses = [
            [
                'title' => 'Onboarding Course - KJO Academy',
                'slug' => 'onboarding-course-kjoacademy',
                'description' => 'Learn the basics of KJO Academy and get familiar with our learning platform',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 3600,
                'is_published' => true,
                'categories' => [
                    $categories['kjoacademy-onboarding']->id => ['is_primary' => true],
                ],
            ],
            [
                'title' => 'How to create Web 3 Apps',
                'slug' => 'how-to-create-web-3-apps',
                'description' => 'Learn to create modern Web 3 applications from scratch',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 7200,
                'is_published' => true,
                'categories' => [
                    $categories['web-3-fundamentals']->id => ['is_primary' => true],
                    $categories['blockchain-fundamentals']->id => ['is_primary' => false],
                ],
            ],
            [
                'title' => 'Fundamentals of Crypto Currency Trading',
                'slug' => 'fundamentals-of-crypto-currency-trading',
                'description' => 'Learn the basics of crypto currency trading and market analysis',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 5400,
                'is_published' => true,
                'categories' => [
                    $categories['crypto-currency-trading']->id => ['is_primary' => true],
                    $categories['defi-decentralized-finance']->id => ['is_primary' => false],
                ],
            ],
            [
                'title' => 'Smart Contract Development',
                'slug' => 'smart-contract-development',
                'description' => 'Learn to develop, test, and deploy smart contracts on various blockchains',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 10800,
                'is_published' => true,
                'categories' => [
                    $categories['smart-contracts']->id => ['is_primary' => true],
                    $categories['blockchain-fundamentals']->id => ['is_primary' => false],
                    $categories['web-3-fundamentals']->id => ['is_primary' => false],
                ],
            ],
            [
                'title' => 'DeFi Protocols Deep Dive',
                'slug' => 'defi-protocols-deep-dive',
                'description' => 'Explore popular DeFi protocols and learn how to interact with them',
                'thumbnail_url' => 'https://placehold.co/400',
                'enrollment_count' => 0,
                'duration_seconds' => 9000,
                'is_published' => true,
                'categories' => [
                    $categories['defi-decentralized-finance']->id => ['is_primary' => true],
                    $categories['crypto-currency-trading']->id => ['is_primary' => false],
                    $categories['smart-contracts']->id => ['is_primary' => false],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $categories = $courseData['categories'];
            unset($courseData['categories']);

            $course = Course::create($courseData);
            $course->categories()->attach($categories);
        }
    }
}

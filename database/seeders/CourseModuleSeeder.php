<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Database\Seeder;

class CourseModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all courses
        $courses = Course::all()->keyBy('slug');

        // Define modules for each course
        $modulesData = [
            'onboarding-course-kjoacademy' => [
                [
                    'title' => 'Welcome to KJO Academy',
                    'order' => 1,
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
                [
                    'title' => 'Platform Navigation',
                    'order' => 2,
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
                [
                    'title' => 'Your Learning Dashboard',
                    'order' => 3,
                    'duration_seconds' => 1500,
                    'is_published' => true,
                ],
            ],
            'how-to-create-web-3-apps' => [
                [
                    'title' => 'Introduction to Web 3',
                    'order' => 1,
                    'duration_seconds' => 1800,
                    'is_published' => true,
                ],
                [
                    'title' => 'Blockchain Fundamentals',
                    'order' => 2,
                    'duration_seconds' => 2400,
                    'is_published' => true,
                ],
                [
                    'title' => 'Smart Contracts Basics',
                    'order' => 3,
                    'duration_seconds' => 3000,
                    'is_published' => true,
                ],
            ],
            'fundamentals-of-crypto-currency-trading' => [
                [
                    'title' => 'Introduction to Cryptocurrency',
                    'order' => 1,
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
                [
                    'title' => 'Market Analysis Techniques',
                    'order' => 2,
                    'duration_seconds' => 1800,
                    'is_published' => true,
                ],
                [
                    'title' => 'Trading Strategies',
                    'order' => 3,
                    'duration_seconds' => 2400,
                    'is_published' => true,
                ],
            ],
            'smart-contract-development' => [
                [
                    'title' => 'Solidity Fundamentals',
                    'order' => 1,
                    'duration_seconds' => 3600,
                    'is_published' => true,
                ],
                [
                    'title' => 'Smart Contract Architecture',
                    'order' => 2,
                    'duration_seconds' => 4200,
                    'is_published' => true,
                ],
                [
                    'title' => 'Testing and Deployment',
                    'order' => 3,
                    'duration_seconds' => 3000,
                    'is_published' => true,
                ],
            ],
            'defi-protocols-deep-dive' => [
                [
                    'title' => 'DeFi Ecosystem Overview',
                    'order' => 1,
                    'duration_seconds' => 2400,
                    'is_published' => true,
                ],
                [
                    'title' => 'Lending Protocols',
                    'order' => 2,
                    'duration_seconds' => 3300,
                    'is_published' => true,
                ],
                [
                    'title' => 'DEX and Yield Farming',
                    'order' => 3,
                    'duration_seconds' => 3300,
                    'is_published' => true,
                ],
            ],
        ];

        foreach ($modulesData as $courseSlug => $modules) {
            $course = $courses->get($courseSlug);
            
            if ($course) {
                foreach ($modules as $moduleData) {
                    $moduleData['course_id'] = $course->id;
                    CourseModule::create($moduleData);
                }
            }
        }
    }
}

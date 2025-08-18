<?php

namespace Database\Seeders;

use App\Models\CourseLesson;
use App\Models\CourseModule;
use Illuminate\Database\Seeder;

class CourseLessonSeeder extends Seeder
{
    public function run(): void
    {
        // Get all modules
        $modules = CourseModule::all()->keyBy('title');

        // Define lessons for each module
        $lessonsData = [
            // Onboarding Course Modules
            'Welcome to KJO Academy' => [
                [
                    'title' => 'Course Overview',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/welcome-overview.mp4',
                    'duration_seconds' => 300,
                    'is_published' => true,
                ],
                [
                    'title' => 'Getting Started Guide',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/getting-started.pdf',
                    'duration_seconds' => 600,
                    'is_published' => true,
                ],
            ],
            'Platform Navigation' => [
                [
                    'title' => 'Dashboard Walkthrough',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/dashboard-walkthrough.mp4',
                    'duration_seconds' => 450,
                    'is_published' => true,
                ],
                [
                    'title' => 'Navigation Quiz',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_QUIZ,
                    'lesson_content_url' => 'https://example.com/quizzes/navigation-quiz.json',
                    'duration_seconds' => 750,
                    'is_published' => true,
                ],
            ],
            'Your Learning Dashboard' => [
                [
                    'title' => 'Progress Tracking',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/progress-tracking.mp4',
                    'duration_seconds' => 600,
                    'is_published' => true,
                ],
                [
                    'title' => 'Dashboard Assignment',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_ASSIGNMENT,
                    'lesson_content_url' => 'https://example.com/assignments/dashboard-setup.pdf',
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
            ],

            // Web 3 Apps Course Modules
            'Introduction to Web 3' => [
                [
                    'title' => 'What is Web 3?',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/what-is-web3.mp4',
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
                [
                    'title' => 'Web 3 vs Web 2 Comparison',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/web3-vs-web2.pdf',
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
            ],
            'Blockchain Fundamentals' => [
                [
                    'title' => 'Blockchain Basics',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/blockchain-basics.mp4',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
                [
                    'title' => 'Consensus Mechanisms',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/consensus-mechanisms.pdf',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
            ],
            'Smart Contracts Basics' => [
                [
                    'title' => 'Smart Contract Introduction',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/smart-contract-intro.mp4',
                    'duration_seconds' => 1500,
                    'is_published' => true,
                ],
                [
                    'title' => 'Smart Contract Quiz',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_QUIZ,
                    'lesson_content_url' => 'https://example.com/quizzes/smart-contract-basics.json',
                    'duration_seconds' => 1500,
                    'is_published' => true,
                ],
            ],

            // Crypto Trading Course Modules
            'Introduction to Cryptocurrency' => [
                [
                    'title' => 'What is Cryptocurrency?',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/what-is-cryptocurrency.mp4',
                    'duration_seconds' => 600,
                    'is_published' => true,
                ],
                [
                    'title' => 'Cryptocurrency Types',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/cryptocurrency-types.pdf',
                    'duration_seconds' => 600,
                    'is_published' => true,
                ],
            ],
            'Market Analysis Techniques' => [
                [
                    'title' => 'Technical Analysis Basics',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/technical-analysis.mp4',
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
                [
                    'title' => 'Chart Patterns',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/chart-patterns.pdf',
                    'duration_seconds' => 900,
                    'is_published' => true,
                ],
            ],
            'Trading Strategies' => [
                [
                    'title' => 'Day Trading Strategies',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/day-trading-strategies.mp4',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
                [
                    'title' => 'Trading Assignment',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_ASSIGNMENT,
                    'lesson_content_url' => 'https://example.com/assignments/trading-practice.pdf',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
            ],

            // Smart Contract Development Course Modules
            'Solidity Fundamentals' => [
                [
                    'title' => 'Solidity Syntax',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/solidity-syntax.mp4',
                    'duration_seconds' => 1800,
                    'is_published' => true,
                ],
                [
                    'title' => 'Variables and Data Types',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/solidity-variables.mp4',
                    'duration_seconds' => 1800,
                    'is_published' => true,
                ],
            ],
            'Smart Contract Architecture' => [
                [
                    'title' => 'Contract Structure',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/contract-structure.mp4',
                    'duration_seconds' => 2100,
                    'is_published' => true,
                ],
                [
                    'title' => 'Architecture Patterns',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/architecture-patterns.pdf',
                    'duration_seconds' => 2100,
                    'is_published' => true,
                ],
            ],
            'Testing and Deployment' => [
                [
                    'title' => 'Testing Smart Contracts',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/testing-contracts.mp4',
                    'duration_seconds' => 1500,
                    'is_published' => true,
                ],
                [
                    'title' => 'Deployment Guide',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/deployment-guide.pdf',
                    'duration_seconds' => 1500,
                    'is_published' => true,
                ],
            ],

            // DeFi Course Modules
            'DeFi Ecosystem Overview' => [
                [
                    'title' => 'What is DeFi?',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/what-is-defi.mp4',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
                [
                    'title' => 'DeFi Ecosystem Map',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/defi-ecosystem.pdf',
                    'duration_seconds' => 1200,
                    'is_published' => true,
                ],
            ],
            'Lending Protocols' => [
                [
                    'title' => 'Compound Protocol',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/compound-protocol.mp4',
                    'duration_seconds' => 1650,
                    'is_published' => true,
                ],
                [
                    'title' => 'Aave Protocol',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/aave-protocol.mp4',
                    'duration_seconds' => 1650,
                    'is_published' => true,
                ],
            ],
            'DEX and Yield Farming' => [
                [
                    'title' => 'Uniswap Deep Dive',
                    'order' => 1,
                    'lesson_type' => CourseLesson::TYPE_VIDEO,
                    'lesson_content_url' => 'https://example.com/videos/uniswap-deep-dive.mp4',
                    'duration_seconds' => 1650,
                    'is_published' => true,
                ],
                [
                    'title' => 'Yield Farming Strategies',
                    'order' => 2,
                    'lesson_type' => CourseLesson::TYPE_TEXT,
                    'lesson_content_url' => 'https://example.com/content/yield-farming.pdf',
                    'duration_seconds' => 1650,
                    'is_published' => true,
                ],
            ],
        ];

        foreach ($lessonsData as $moduleTitle => $lessons) {
            $module = $modules->get($moduleTitle);
            
            if ($module) {
                foreach ($lessons as $lessonData) {
                    $lessonData['course_module_id'] = $module->id;
                    CourseLesson::create($lessonData);
                }
            }
        }
    }
}

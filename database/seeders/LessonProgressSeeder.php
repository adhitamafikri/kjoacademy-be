<?php

namespace Database\Seeders;

use App\Models\CourseLesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class LessonProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Get enrollments with their courses and lessons
        $enrollments = Enrollment::with(['course.modules.lessons'])->get();

        if ($enrollments->isEmpty()) {
            return; // No enrollments to create progress for
        }

        foreach ($enrollments as $enrollment) {
            // Check if course has modules
            if (!$enrollment->course->modules || $enrollment->course->modules->isEmpty()) {
                continue;
            }

            // Create lesson progress for each lesson in the course
            foreach ($enrollment->course->modules as $module) {
                // Check if module has lessons
                if (!$module->lessons || $module->lessons->isEmpty()) {
                    continue;
                }

                foreach ($module->lessons as $lesson) {
                    // Randomly decide if user has started this lesson
                    $hasStarted = rand(0, 1);
                    
                    if ($hasStarted) {
                        $isCompleted = rand(0, 1);
                        $status = $isCompleted ? 'completed' : 'in_progress';
                        
                        // Calculate realistic progress based on lesson type
                        $timeSpent = 0;
                        $videoProgress = 0;
                        $attempts = 0;
                        $score = null;
                        
                        if ($lesson->isVideoLesson()) {
                            if ($isCompleted) {
                                $timeSpent = $lesson->duration_seconds;
                                $videoProgress = $lesson->duration_seconds;
                            } else {
                                $timeSpent = rand(0, $lesson->duration_seconds);
                                $videoProgress = rand(0, $lesson->duration_seconds);
                            }
                        } elseif ($lesson->isQuizLesson()) {
                            $attempts = rand(1, 3);
                            $score = $isCompleted ? rand(70, 100) : rand(0, 69);
                            $timeSpent = rand(300, 1800); // 5-30 minutes for quiz
                        } else {
                            $timeSpent = rand(60, 600); // 1-10 minutes for text/assignment
                        }
                        
                        LessonProgress::create([
                            'user_id' => $enrollment->user_id,
                            'course_lesson_id' => $lesson->id,
                            'course_enrollment_id' => $enrollment->id,
                            'status' => $status,
                            'started_at' => now()->subDays(rand(1, 30)),
                            'completed_at' => $isCompleted ? now()->subDays(rand(0, 10)) : null,
                            'time_spent_seconds' => $timeSpent,
                            'video_progress_seconds' => $videoProgress,
                            'last_accessed_at' => now()->subDays(rand(0, 7)),
                            'attempts_count' => $attempts,
                            'score' => $score,
                        ]);
                    }
                }
            }
        }
    }
}

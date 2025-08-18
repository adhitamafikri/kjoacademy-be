<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\ModuleProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModuleProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Get enrollments with their courses and modules
        $enrollments = Enrollment::with(['course.modules'])->get();

        if ($enrollments->isEmpty()) {
            return; // No enrollments to create progress for
        }

        foreach ($enrollments as $enrollment) {
            // Check if course has modules
            if (!$enrollment->course->modules || $enrollment->course->modules->isEmpty()) {
                continue;
            }

            // Create module progress for each module in the course
            foreach ($enrollment->course->modules as $module) {
                // Randomly decide if user has started this module
                $hasStarted = rand(0, 1);
                
                if ($hasStarted) {
                    $progressPercentage = rand(0, 100);
                    $status = $progressPercentage >= 100 ? 'completed' : ($progressPercentage > 0 ? 'in_progress' : 'not_started');
                    
                    ModuleProgress::create([
                        'user_id' => $enrollment->user_id,
                        'course_module_id' => $module->id,
                        'course_enrollment_id' => $enrollment->id,
                        'status' => $status,
                        'progress_percentage' => $progressPercentage,
                        'lessons_completed_count' => $progressPercentage >= 100 ? $module->lessons()->count() : rand(0, $module->lessons()->count()),
                        'started_at' => $progressPercentage > 0 ? now()->subDays(rand(1, 30)) : null,
                        'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 10)) : null,
                        'last_accessed_at' => $progressPercentage > 0 ? now()->subDays(rand(0, 7)) : null,
                    ]);
                }
            }
        }
    }
}

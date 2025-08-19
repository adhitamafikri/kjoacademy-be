<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->ulid('course_lesson_id');
            $table->unsignedBigInteger('course_enrollment_id');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('video_progress_seconds')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('attempts_count')->default(0);
            $table->decimal('score', 5, 2)->nullable(); // For quizzes/assignments
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_lesson_id')->references('id')->on('course_lessons')->onDelete('cascade');
            $table->foreign('course_enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');

            // Constraints
            $table->unique(['user_id', 'course_lesson_id']); // One progress record per user per lesson

            // Indexes for performance
            $table->index(['user_id', 'course_enrollment_id']);
            $table->index(['course_lesson_id', 'status']);
            $table->index(['course_enrollment_id', 'status']);
            $table->index(['user_id', 'last_accessed_at']);
            $table->index(['user_id', 'video_progress_seconds']); // For video resume functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};

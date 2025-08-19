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
        Schema::create('module_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->ulid('course_module_id');
            $table->unsignedBigInteger('course_enrollment_id');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->integer('progress_percentage')->default(0);
            $table->integer('lessons_completed_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_module_id')->references('id')->on('course_modules')->onDelete('cascade');
            $table->foreign('course_enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');

            // Constraints
            $table->unique(['user_id', 'course_module_id']); // One progress record per user per module

            // Indexes for performance
            $table->index(['user_id', 'course_enrollment_id']);
            $table->index(['course_module_id', 'status']);
            $table->index(['course_enrollment_id', 'status']);
            $table->index(['user_id', 'last_accessed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_progress');
    }
};

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
        Schema::create('course_modules', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('course_id')->nullable(false);
            $table->string('title')->nullable(false);
            $table->integer('order')->nullable(false);
            $table->integer('lessons_count')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // foreign keys
            $table->foreign('course_id')->references('id')->on('courses');

            // indexes
            $table->index(['course_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};

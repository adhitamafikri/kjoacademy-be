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
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('course_module_id')->nullable(false);
            $table->string('title')->nullable(false);
            $table->integer('order')->nullable(false);
            $table->string('lesson_type')->nullable(false);
            $table->string('lesson_content_url')->nullable(false);
            $table->integer('duration_seconds')->nullable(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // foreign keys
            $table->foreign('course_module_id')->references('id')->on('course_modules');

            // indexes
            $table->index(['course_module_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};

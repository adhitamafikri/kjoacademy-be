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
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('category_id')->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('slug')->unique()->nullable(false);
            $table->text('description')->nullable(false);
            $table->text('thumbnail_url')->nullable(false);
            $table->integer('enrollment_count')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->boolean('is_published')->default(false);
            $table->json('metadata')->nullable(false);
            $table->timestamps();
            $table->softDeletes();

            // constraints
            $table->foreign('category_id')->references('id')->on('course_categories');

            // indexes
            $table->index(['category_id', 'is_published', 'created_at']);
            $table->index(['enrollment_count', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

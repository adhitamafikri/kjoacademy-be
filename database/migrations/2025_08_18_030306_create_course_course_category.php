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
        Schema::create('course_course_category', function (Blueprint $table) {
            $table->ulid('course_id')->nullable(false);
            $table->ulid('course_category_id')->nullable(false);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // foreign keys
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('course_category_id')->references('id')->on('course_categories');

            // constraints
            $table->primary(['course_id', 'course_category_id']);

            // indexes
            $table->index(['course_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_category_course');
    }
};

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
            $table->string('title')->nullable(false);
            $table->string('slug')->unique()->nullable(false);
            $table->text('description')->nullable(false);
            $table->text('thumbnail_url')->nullable(false);
            $table->integer('enrollment_count')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->boolean('is_published')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // indexes
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

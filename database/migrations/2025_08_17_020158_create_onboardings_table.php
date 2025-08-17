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
        Schema::create('onboardings', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('user_id')->nullable(false);
            $table->tinyInteger('onboarding_step')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamps();

            // constraints
            $table->foreignId('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboardings');
    }
};

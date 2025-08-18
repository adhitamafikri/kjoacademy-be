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
        Schema::create('roles', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->string('name')->unique()->nullable(false);
            $table->text('description')->nullable(false);
            $table->json('permissions')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('role_id')->nullable(false);
            $table->string('name')->default('');
            $table->string('phone')->unique()->nullable(false);
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->timestamp('onboarding_started_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // foreign keys
            $table->foreign('role_id')->references('id')->on('roles');

            // Add indexes for better performance
            $table->index(['role_id', 'email']);
            $table->index(['phone']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('payload', 255)->nullable(false);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid('user_id')->nullable(false);
            $table->ipAddress('ip_address')->nullable(false);
            $table->json('device_info')->nullable(false);
            $table->text('user_agent')->nullable(false);
            $table->string('payload', 255)->nullable(false);
            $table->timestamp('expires_at')->nullable(false);
            $table->timestamp('created_at');
            $table->timestamp('last_activity');
            $table->softDeletes();

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users');

            // Add indexes for session management
            $table->index(['user_id', 'expires_at']);
            $table->index(['expires_at']); // For cleanup operations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

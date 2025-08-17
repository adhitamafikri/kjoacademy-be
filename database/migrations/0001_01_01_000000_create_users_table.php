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
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->integer('role_id')->nullable(false);
            $table->string('name')->default('');
            $table->string('phone')->unique()->nullable(false);
            $table->string('email')->unique()->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default('');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // constraints
            $table->foreignId('role_id')->references('id')->on('roles');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->ipAddress('ip_address')->nullable(false);
            $table->json('device_info')->nullable(false);
            $table->text('user_agent')->nullable(false);
            $table->text('token')->nullable(false);
            $table->timestamp('expires_at')->nullable(false);
            $table->timestamp('created_at');
            $table->timestamp('last_accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

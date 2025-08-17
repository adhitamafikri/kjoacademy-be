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
        //
        Schema::create('session_logs', function (Blueprint $table) {
            $table->ulid('id')->primary()->nullable(false);
            $table->ulid(column: 'user_id')->nullable(false);
            $table->text('token')->nullable(false);
            $table->string('action')->nullable(false);
            $table->json('device_info')->nullable(false);
            $table->ipAddress('ip_address')->nullable(false);
            $table->text('user_agent')->nullable(false);
            $table->timestamp('created_at')->nullable(false);

            // constraints
            $table->foreignId('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('session_logs');
    }
};

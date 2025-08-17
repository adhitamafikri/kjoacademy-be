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
        Schema::create('otp', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable(value: false);
            $table->char('otp_code', 6)->nullable(false);
            $table->timestamp('expires_at')->nullable(false);
            $table->boolean('used')->default(false);
            $table->timestamp('created_at');

            // constraints
            $table->foreignId('user_id')->references('id')->on('users')
;        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};

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
        Schema::create('otps', function (Blueprint $table) {
            $table->id()->primary()->nullable(false);
            $table->ulid('user_id')->nullable(false);
            $table->char('otp_code', 6)->nullable(false);
            $table->string('purpose', 50)->nullable(false);
            $table->timestamp('expires_at')->nullable(false);
            $table->timestamp('verified_at')->nullable(false);
            $table->tinyInteger('attempts')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // constraints
            $table->unique(['user_id', 'otp_code']);

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users');

            // indexes
            $table->index(['user_id', 'expires_at']); // Active OTPs per user
            $table->index(['otp_code', 'user_id']); // OTP verification (unique constraint)
            $table->index(['expires_at', 'verified_at']); // Cleanup operations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

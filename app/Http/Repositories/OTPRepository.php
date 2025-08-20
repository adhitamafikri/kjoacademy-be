<?php

namespace App\Http\Repositories;

use DateTimeInterface;
use App\Models\OTP;
use App\Constants\TokenPurpose;

class OTPRepository
{
    public function create(string $user_id, string $otp_code, TokenPurpose $purpose, DateTimeInterface $expires_at)
    {
        return OTP::create([
            "user_id" => $user_id,
            "otp_code" => $otp_code,
            "purpose" => $purpose,
            "expires_at" => $expires_at,
        ]);
    }

    public function findActive(string $user_id, TokenPurpose $purpose)
    {
        return OTP::where('user_id', $user_id)
            ->where('purpose', $purpose)
            ->where('expires_at', '>', now())
            ->where('verified_at', null)
            ->first();
    }
}

<?php

namespace App\Http\Services;

use App\Constants\TokenPurpose;
use App\Http\Repositories\OTPRepository;
use App\Models\User;

class OTPService
{
    public function __construct(private OTPRepository $otpRepository) {}

    public function generateNumericOTP(User $user, TokenPurpose $purpose, int $length = 6)
    {
        // Ensure minimum length
        $length = max(1, $length);

        // Generate a secure random integer
        $min = (int) str_pad('1', $length, '0');      // e.g. 100000 for length 6
        $max = (int) str_pad('', $length, '9');       // e.g. 999999 for length 6

        $payload = [
            'user_id' => $user->id,
            'otp_code' => (string) random_int($min, $max), // cryptographically secure
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
        ];
        $otp = $this->otpRepository->create(...$payload);
        return $otp;
    }

    public function sendOTP(User $user, string $phone, string $otp)
    {
        // TODO: Implement the actual sending of the OTP to the user's phone number
    }

    public function getActiveOTP(string $user_id, TokenPurpose $purpose)
    {
        $active_otp = $this->otpRepository->findActive($user_id, $purpose);
        if ($active_otp) {
            return $active_otp;
        }
        return null;
    }
}

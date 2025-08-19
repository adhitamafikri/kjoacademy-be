<?php

namespace App\Http\Services;

use DateTimeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Constants\TokenPurpose;
use App\Models\User;
use App\Http\Repositories\UserRepository;
use App\Http\Services\OTPService;

class AuthService
{
    public function __construct(private UserRepository $userRepository, private OTPService $otpService) {}

    public function requestOTP(Request $request)
    {
        /**
         * @TODO: Implement the request OTP logic
         * 1. Look up the phone number in the 'users' table
         * 2. If the phone number is not found, proceed to lookup for the phone number in the given Google Sheet containing KJO Academy's members
         *  2.1. If the phone number is not found in the Google Sheet, return an error response
         *  2.2. If the phone number is found in the Google Sheet, proceed to create a new user record in the 'users' table
         * 3. Generate OTP
         * 4. Send OTP to the user's phone number
         * 5. Return a success response
         */
        $user = $this->userRepository->findByPhone($request->phone);

        // Lookup the user in the Google Sheet - TBD
        // Save the user to the database - TBD

        // IF NOT FOUND, return an error response
        if (!$user) {
            return null;
        }

        // If there's still an active OTP, return an error response
        $active_otp = $this->otpService->getActiveOTP($user->id, TokenPurpose::from($request->purpose));
        if ($active_otp !== null) throw new Exception('You have an active OTP. Please wait for it to expire before requesting a new one.');

        // Generate OTP Code and save it to the database
        $request->validate([
            'purpose' => [new Enum(TokenPurpose::class),]
        ]);
        $otp = $this->otpService->generateNumericOTP($user, TokenPurpose::from($request->purpose));
        // Send OTP to the user's phone number
        $this->otpService->sendOTP($user, $request->phone, $otp['otp_code']);

        return 'OTP sent successfully';
    }

    public function verifyOTP(Request $request)
    {
        $user = $this->userRepository->findByPhone($request->phone);
        $active_otp = $this->otpService->getActiveOTP($user->id, TokenPurpose::from($request->purpose));

        // OTP not found
        if ($active_otp === null) throw new Exception('OTP code not found');
        // OTP found - expired
        if ($active_otp->expires_at < now()) throw new Exception('OTP code expired');
        // OTP found - already verified
        if ($active_otp->verified_at !== null) throw new Exception('OTP already verified');

        // OTP found - Will verify
        if ($active_otp->expires_at > now() && $active_otp->verified_at === null) {
            // verify the OTP
            $active_otp->verified_at = now();
            $active_otp->save();

            // create a new token
            $user = $active_otp->user;
            $this->createAccessToken(
                $user,
                'student-login-token',
                ['*'],
                now()->addMinutes(15)
            );

            return 'OTP verified successfully';
        }
    }

    private function createAccessToken(User $user, string $token_name, array $abilities, DateTimeInterface $expiresAt)
    {
        $token = $user->createToken(
            $token_name,
            $abilities,
            $expiresAt
        )->plainTextToken;
        return $token;
    }
}

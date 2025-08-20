<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Repositories\UserRepository;
use App\Http\Services\OTPService;
use App\Http\Services\AuthService;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService,
        private OTPService $otpService,
        private UserRepository $userRepository
    ) {}

    public function requestOTP(Request $request)
    {
        try {
            $message = $this->authService->requestOTP($request);
            return response()->json([
                "message" => $message,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            [$message, $accessToken] = $this->authService->verifyOTP($request);
            return response()->json([
                "message" => $message,
                "access_token" => $accessToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function resendOTP(Request $request) {}

    public function refreshSession(Request $request) {}

    public function logout(Request $request) {}
}

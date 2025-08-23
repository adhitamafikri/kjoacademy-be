<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    /**
     * Display a listing of the resource.
     */
    public function index() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

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
            [$message, $accessToken, $userData] = $this->authService->verifyOTP($request);
            return response()->json([
                "message" => $message,
                "access_token" => $accessToken,
                "user" => $userData,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function resendOTP(Request $request) {}

    public function refreshSession(Request $request) {}

    public function logout(Request $request)
    {
        try {
            // Revoke the current user's access token
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                "message" => "Logged out successfully",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Logout failed: " . $e->getMessage(),
            ], 500);
        }
    }
}

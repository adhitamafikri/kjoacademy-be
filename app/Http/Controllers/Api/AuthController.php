<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Http\Repositories\UserRepository;
use App\Http\Services\OTPService;

class AuthController extends Controller
{
    public function __construct(
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
            $message = $this->otpService->requestOTP($request);
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
            [$message, $accessToken, $userData] = $this->otpService->verifyOTP($request);
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

    public function resendOTP(Request $request) 
    {
        try {
            $message = $this->otpService->resendOTP($request);
            return response()->json([
                "message" => $message,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function refreshSession(Request $request)
    {
        try {
            [$message, $accessToken, $userData] = $this->otpService->refreshSession($request);
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

    public function logout(Request $request)
    {
        try {
            // Revoke the current user's access token
            $request->user()->token()->revoke();
            
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

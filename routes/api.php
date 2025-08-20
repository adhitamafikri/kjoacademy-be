<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\UserController as StudentUserController;
use App\Http\Controllers\Student\CourseCategoryController as StudentCourseCategoryController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Middleware\AuthEndpointGuard;

// v1 auth APIs
// The /auth endpoints are used by Student and Admin
Route::prefix('v1/auth')->group(function () {
    Route::group([
        'middleware' => ['throttle:5,1', AuthEndpointGuard::class],
    ], function () {
        Route::post('/request-otp', [AuthController::class, 'requestOTP']);
        Route::post('/verify-otp', action: [AuthController::class, 'verifyOTP']);
        Route::post('/resend-otp', action: [AuthController::class, 'resendOTP']);
        Route::post('/refresh-session', [AuthController::class, 'refreshSession']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', action: [AuthController::class, 'logout']);
    });
});
// v1 auth APIs:END

// v1 user facing APIs
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->prefix('users')->group(function () {
        Route::get('/me', [StudentUserController::class, 'getMe']);
    });

    Route::middleware('auth:sanctum')->prefix('categories')->group(function () {
        Route::get('/', [StudentCourseCategoryController::class, 'getCategories']);
        Route::get('/{slug}', [StudentCourseCategoryController::class, 'getCategoryBySlug']);
    });

    Route::middleware('auth:sanctum')->prefix('courses')->group(function () {
        Route::get('/', [StudentCourseController::class, 'getCourses']);
        Route::get('/me', [StudentCourseController::class, 'getMyCourses']);
        Route::get('/{slug}', [StudentCourseController::class, 'getCourseBySlug']);
        Route::get('/categories/{slug}', [StudentCourseController::class, 'getCoursesByCategory']);
    });
});
// v1 user facing APIs:END

// v1 admin facing APIs
Route::prefix('v1/admin')->group(function () {});
// v1 admin facing APIs:END
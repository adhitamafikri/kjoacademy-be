<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\UserController as StudentUserController;
use App\Http\Controllers\Student\CourseCategoryController as StudentCourseCategoryController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
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
        Route::post('/logout', action: [AuthController::class, 'logout']);
        Route::post('/refresh-session', [AuthController::class, 'refreshSession']);
    });
});
// v1 auth APIs:END

// v1 user facing APIs
Route::prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/me', [StudentUserController::class, 'getMe']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [StudentCourseCategoryController::class, 'getCategories']);
        Route::get('/{id}', [StudentCourseCategoryController::class, 'getCategoryById']);
    });

    Route::prefix('courses')->group(function () {
        Route::get('/', [StudentCourseController::class, 'getCourse']);
        Route::get('/{id}', [StudentCourseController::class, 'getCourseById']);
        Route::get('/me', [StudentCourseController::class, 'getMyCourses']);
    });
});
// v1 user facing APIs:END

// v1 admin facing APIs
Route::prefix('v1/admin')->group(function () {});
// v1 admin facing APIs:END
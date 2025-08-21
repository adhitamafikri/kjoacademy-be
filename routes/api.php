<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Middleware\AuthEndpointGuard;

// v1 auth APIs
// The /auth endpoints are used by Student and Admin
Route::prefix('v1/auth')->group(function () {
    Route::group([
        'middleware' => ['throttle:5,1', AuthEndpointGuard::class],
    ], function () {
        Route::post('/request-otp', [Api\AuthController::class, 'requestOTP']);
        Route::post('/verify-otp', action: [Api\AuthController::class, 'verifyOTP']);
        Route::post('/resend-otp', action: [Api\AuthController::class, 'resendOTP']);
        Route::post('/refresh-session', [Api\AuthController::class, 'refreshSession']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', action: [Api\AuthController::class, 'logout']);
    });
});
// v1 auth APIs:END

// v1 user facing APIs
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->prefix('users')->group(function () {
        Route::get('/me', [UserController::class, 'getMe']);
    });

    Route::middleware('auth:sanctum')->prefix('categories')->group(function () {
        Route::get('/', [Api\Student\CourseCategoryController::class, 'getCategories']);
        Route::get('/{slug}', [Api\Student\CourseCategoryController::class, 'getCategoryBySlug']);
    });

    Route::middleware('auth:sanctum')->prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'getCourses']);
        Route::get('/me', [CourseController::class, 'getMyCourses']);
        Route::get('/{slug}', [CourseController::class, 'getCourseBySlug']);
        Route::get('/categories/{slug}', [CourseController::class, 'getCoursesByCategory']);
    });
});
// v1 user facing APIs:END

// v1 admin facing APIs
Route::prefix('v1/admin')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/me', [UserController::class, 'getMe']);
        });

        Route::apiResource('categories', Api\Admin\CourseCategoryController::class)
            ->parameters(['categories' => 'slug']);

        Route::prefix('courses')->group(function () {
            Route::get('/', [CourseController::class, 'getCourses']);
            Route::post('/', [CourseController::class, 'createCourse']);
            Route::get('/categories/{slug}', [CourseController::class, 'getCoursesByCategory']);
            Route::get('/{slug}', [CourseController::class, 'getCourseBySlug']);
            Route::put('/{slug}', [CourseController::class, 'updateCourse']);
            Route::delete('/{slug}', [CourseController::class, 'deleteCourse']);
        });
    });
});
// v1 admin facing APIs:END
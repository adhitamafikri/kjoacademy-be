<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
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

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', action: [Api\AuthController::class, 'logout']);
    });
});
// v1 auth APIs:END

// v1 user facing APIs
Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'role:student'])->prefix('users')->group(function () {
        Route::get('/me', [Api\UserController::class, 'getMe']);
    });

    Route::middleware(['auth:api', 'role:student'])->prefix('categories')->group(function () {
        Route::get('/', [Api\Student\CourseCategoryController::class, 'index']);
        Route::get('/{slug}', [Api\Student\CourseCategoryController::class, 'show']);
    });

    Route::middleware(['auth:api', 'role:student'])->prefix('courses')->group(function () {
        Route::get('/', [Api\Student\CourseController::class, 'index']);
        Route::get('/me', [Api\Student\CourseController::class, 'getMyCourses']);
        Route::get('/{slug}', [Api\Student\CourseController::class, 'show']);
        Route::get('/categories/{slug}', [Api\Student\CourseController::class, 'getCoursesByCategory']);
    });
});
// v1 user facing APIs:END

// v1 admin facing APIs
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['auth:api', 'role:admin'])->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/me', [Api\UserController::class, 'getMe']);
        });

        Route::apiResource('categories', Api\Admin\CourseCategoryController::class)
            ->parameters(['categories' => 'slug']);

        Route::prefix('courses')->group(function () {
            Route::get('/', [Api\Admin\CourseController::class, 'index']);
            Route::post('/', [Api\Admin\CourseController::class, 'store']);
            Route::get('/categories/{slug}', [Api\Admin\CourseController::class, 'getCoursesByCategory']);
            Route::get('/{slug}', [Api\Admin\CourseController::class, 'show']);
            Route::put('/{slug}', [Api\Admin\CourseController::class, 'update']);
            Route::delete('/{slug}', [Api\Admin\CourseController::class, 'destroy']);
        });

        Route::apiResource('modules', Api\Admin\CourseModuleController::class)
            ->parameters(['modules' => 'id']);

        Route::apiResource('lessons', Api\Admin\CourseLessonController::class)
            ->parameters(['lessons' => 'id']);
    });
});
// v1 admin facing APIs:END
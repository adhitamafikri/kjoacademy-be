<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// User facing APIs
Route::prefix('v1')->group(function () {
    // User facing Auth APIs
    Route::prefix('auth')->group(function () {
        Route::post('/login', function () {
            return [
                'message' => 'This is login path'
            ];
        });
        Route::post('/refresh-session', function () {
            return [
                'message' => 'This is path to refresh session'
            ];
        });
        Route::post('/logout', function () {
            return [
                'message' => 'This is path to logout session'
            ];
        });
    });

    // Course Categories APIs
    Route::prefix('categories')->group(function () {
        Route::get('/', function () {
            return [
                'message' => 'This is GET course categories path'
            ];
        });

        Route::get('/{id}', action: function () {
            return [
                'message' => 'This is GET course category BY ID path'
            ];
        });
    });

    // Courses APIs
    Route::prefix('courses')->group(function () {
        Route::get('/', function () {
            return [
                'message' => 'This is GET courses path'
            ];
        });

        Route::get('/{id}', action: function () {
            return [
                'message' => 'This is GET course BY ID path'
            ];
        });
    });
});

// Admin facing APIs
Route::prefix('v1/admin')->group(function () {
    // Admin facing Auth APIs
    Route::prefix('auth')->group(function () {
        Route::post('/login', function () {
            return [
                'message' => 'This is login path'
            ];
        });
        Route::post('/refresh-session', function () {
            return [
                'message' => 'This is path to refresh session'
            ];
        });
        Route::post('/logout', function () {
            return [
                'message' => 'This is path to logout session'
            ];
        });
    });

    // Admin facing Courses Categories APIs
    Route::get('/courses', function () {
        return [
            'message' => 'This is admin courses path'
        ];
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', function () {
            return [
                'message' => 'This is GET course categories path'
            ];
        });

        Route::get('/{id}', action: function () {
            return [
                'message' => 'This is GET course category BY ID path'
            ];
        });
    });

    // Admin facing Courses APIs
    Route::prefix('courses')->group(function () {
        Route::get('/', function () {
            return [
                'message' => 'This is GET courses path'
            ];
        });

        Route::get('/{id}', action: function () {
            return [
                'message' => 'This is GET course BY ID path'
            ];
        });
    });
});

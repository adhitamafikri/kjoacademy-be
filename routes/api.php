<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// v1 user facing APIs
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', function () {
            return [
                "message" => "This is the login path"
            ];
        });
        Route::post('/logout', function () {
            return [
                "message" => "This is the logout path"
            ];
        });
        Route::post('/refresh-token', function () {
            return [
                "message" => "This is the refresh token path"
            ];
        });
    });

    Route::prefix('users')->group(function () {
        Route::get('/me', function () {
            return [
                "message" => "This is the me path"
            ];
        });
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', function () {
            return [
                "message" => "This is get categories path"
            ];
        });
        Route::get('/{id}', function () {
            return [
                "message" => "This is get category by ID path"
            ];
        });
    });

    Route::prefix('courses')->group(function () {
        Route::get('/', function () {
            return [
                "message" => "This is get courses path"
            ];
        });
        Route::get('/{id}', function () {
            return [
                "message" => "This is get course by ID path"
            ];
        });
    });
});
// v1 user facing APIs:END

// v1 admin facing APIs
Route::prefix('v1/admin')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', function () {
            return [
                "message" => "This is the login path"
            ];
        });
        Route::post('/logout', function () {
            return [
                "message" => "This is the logout path"
            ];
        });
        Route::post('/refresh-token', function () {
            return [
                "message" => "This is the refresh token path"
            ];
        });
    });
});
// v1 admin facing APIs:END
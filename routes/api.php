<?php

use App\Http\Controllers\ResponseFileController;
use App\Http\Controllers\RequestFileController;
use App\Http\Controllers\UserFileController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', function (Request $request) {
        return $request->user();
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome, Admin!']);
        });
        Route::apiResource('users', UserController::class)->except(['show']);
    });

    // User specific routes (can also be accessed by admin)
    Route::middleware('role:user,admin')->group(function () {
        Route::prefix('/profile')->group(function () {
            Route::get('/', function (Request $request) {
                return response()->json(['message' => 'This is your profile', 'user' => $request->user()]);
            });
            Route::put('/', [UserController::class, 'updateProfile']);
        });
        
        Route::apiResource('requests', RequestController::class);
        Route::apiResource('responses', ResponseController::class);
        Route::apiResource('request-files', RequestFileController::class);
        Route::apiResource('response-files', ResponseFileController::class);
    });
});

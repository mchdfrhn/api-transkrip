<?php

use App\Http\Controllers\NotificationController;
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
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::get('/profile', function (Request $request) {
            return response()->json(['message' => 'This is your profile', 'user' => $request->user()]);
        });
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('/requests/user/{id_user}', [RequestController::class, 'getUserRequests']);
        Route::apiResource('requests', RequestController::class);
        Route::get('/myRequests', [RequestController::class, 'myRequest'] );
        Route::apiResource('responses', ResponseController::class);
        Route::apiResource('request-files', RequestFileController::class);
        Route::apiResource('response-files', ResponseFileController::class);
        
        // Notification routes
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
            Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
            Route::delete('/{id}', [NotificationController::class, 'destroy']);
            Route::delete('/read/all', [NotificationController::class, 'deleteAllRead']);
        });
    });
});

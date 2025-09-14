<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseTestController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // User Management
    Route::prefix('user')->group(function () {
        Route::get('/', [AuthController::class, 'user']);
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    });
    
    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Legacy route (for backward compatibility)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Database Test Routes (for development)
Route::prefix('test')->group(function () {
    Route::get('/db-connection', [DatabaseTestController::class, 'testConnection'])
        ->name('test.db.connection');
    
    Route::get('/table/{tableName}', [DatabaseTestController::class, 'testTableStructure'])
        ->name('test.table.structure');
});
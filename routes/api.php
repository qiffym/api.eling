<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Guest routes
Route::post('login', [AuthController::class, 'login']);

// Auth routes
Route::middleware('auth:sanctum')->group(fn () => [
    // logout
    Route::post('logout', [AuthController::class, 'logout']),

    // Admin
    Route::middleware('auth:sanctum', 'ability:role:admin')->prefix('admin')->group(fn () => [
        Route::resource('resources/users', UserController::class),
    ]),

    // Teacher
    Route::middleware('auth:sanctum', 'ability:role:teacher')->prefix('teacher')->group(fn () => []),

    // Family
    Route::middleware('auth:sanctum', 'ability:role:family')->prefix('family')->group(fn () => []),

    // Student
    Route::middleware('auth:sanctum', 'ability:role:student')->prefix('student')->group(fn () => []),
]);

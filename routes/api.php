<?php

use App\Http\Controllers\Api\Admin\MotivationalWordController;
use App\Http\Controllers\Api\Admin\RombelClassController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Teacher\OnlineClassContentController;
use App\Http\Controllers\Api\Teacher\OnlineClassController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\Users\DetailUserResource;
use Illuminate\Support\Facades\Auth;
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

// Fallback
Route::fallback(fn () => response()->json('Not Found.', 404));

// Auth routes
Route::middleware('auth:sanctum')->group(fn () => [

    // Me
    Route::get('/me', fn () => new DetailUserResource(Auth::user())),

    // Profile
    Route::controller(ProfileController::class)->group(fn () => [
        Route::patch('profile/update-password/{user}', 'updatePassword'),
        Route::patch('profile/update-avatar/{user}', 'updateAvatar'),
        Route::match(['put', 'patch'], 'profile/{user}', 'update'),
        Route::get('profile/{user}', 'show'),
    ]),

    // Role Admin
    Route::middleware('auth:sanctum', 'ability:role:admin')->prefix('admin')->group(fn () => [
        Route::apiResource('resources/users', UserController::class),
        Route::apiResource('resources/rombel-classes', RombelClassController::class),
        Route::apiResource('resources/motivational-words', MotivationalWordController::class),
    ]),

    // Role Teacher
    Route::middleware('auth:sanctum', 'ability:role:teacher')->prefix('teacher')->group(fn () => [
        Route::apiResource('online-classes', OnlineClassController::class),
        Route::apiResource('online-classes/{online_class}/contents', OnlineClassContentController::class),
    ]),

    // Role Family
    Route::middleware('auth:sanctum', 'ability:role:family')->prefix('family')->group(fn () => []),

    // Role Student
    Route::middleware('auth:sanctum', 'ability:role:student')->prefix('student')->group(fn () => []),

    // logout
    Route::post('logout', [AuthController::class, 'logout']),
]);

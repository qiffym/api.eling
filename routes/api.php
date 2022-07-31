<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\ProfileController;
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
        Route::resource('resources/users', UserController::class),
    ]),

    // Role Teacher
    Route::middleware('auth:sanctum', 'ability:role:teacher')->prefix('teacher')->group(fn () => []),

    // Role Family
    Route::middleware('auth:sanctum', 'ability:role:family')->prefix('family')->group(fn () => []),

    // Role Student
    Route::middleware('auth:sanctum', 'ability:role:student')->prefix('student')->group(fn () => []),

    // logout
    Route::post('logout', [AuthController::class, 'logout']),
]);

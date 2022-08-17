<?php

use App\Http\Controllers\Api\Admin\MotivationalWordController;
use App\Http\Controllers\Api\Admin\RombelClassController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\Teacher\AssignmentController;
use App\Http\Controllers\Api\Teacher\ForumController;
use App\Http\Controllers\Api\Teacher\MaterialController;
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

// Auth routes
Route::middleware('auth:sanctum')->group(fn () => [
    // logout
    Route::post('logout', [AuthController::class, 'logout']),

    // Fallback
    Route::fallback(fn () => response()->json(['success' => false, 'message' => 'Not Found.'], 404)),

    // Me
    Route::get('/me', fn () => new DetailUserResource(Auth::user())),

    // Profile
    Route::controller(ProfileController::class)->group(fn () => [
        Route::patch('profile/update-password/{user}', 'updatePassword'),
        Route::patch('profile/update-avatar/{user}', 'updateAvatar'),
        Route::match(['put', 'patch'], 'profile/{user}', 'update'),
        Route::get('profile/{user}', 'show'),
    ]),

    // * Route for Specific Role
    // Role Admin
    Route::middleware('auth:sanctum', 'ability:role:admin')->prefix('admin')->group(fn () => [
        Route::apiResources([
            'resources/users' => UserController::class,
            'resources/rombel-classes' => RombelClassController::class,
            'resources/motivational-words' => MotivationalWordController::class
        ]),
    ]),

    // Role Teacher
    Route::middleware('auth:sanctum', 'ability:role:teacher')->prefix('teacher')->group(fn () => [
        Route::apiResource('online-classes', OnlineClassController::class),
        Route::scopeBindings()->group(fn () => [
            Route::apiResource('online-classes.contents', OnlineClassContentController::class),
            Route::apiResource('online-classes.contents.assignments', AssignmentController::class),
            Route::apiResource('online-classes.contents.materials', MaterialController::class),
            Route::apiResource('online-classes.contents.forums', ForumController::class),
            Route::apiResource('online-classes.contents.forums.comments', CommentController::class)->only(['store', 'update', 'destroy'])
        ]),
    ]),

    // Role Student
    Route::middleware('auth:sanctum', 'ability:role:student')->prefix('student')->group(fn () => []),

    // Role Family
    Route::middleware('auth:sanctum', 'ability:role:family')->prefix('family')->group(fn () => []),
]);

<?php

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
    Route::post('logout', [AuthController::class, 'logout']),
]);

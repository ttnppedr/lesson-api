<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('me', [AuthController::class, 'me']);

    Route::apiResource('lessons', LessonController::class)->only(['store', 'show', 'index', 'update', 'destroy']);

    Route::apiResource('users', UserController::class)->only(['store', 'index']);

    Route::post('lessons/{lesson}/enroll', [EnrollController::class, 'store']);
    Route::post('lessons/{lesson}/cancel', [EnrollController::class, 'destroy']);
    Route::get('enrolls', [EnrollController::class, 'index']);
});

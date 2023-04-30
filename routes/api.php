<?php

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

use App\Http\Controllers\UserController;
use App\Http\Controllers\SubController;

Route::middleware('throttle:register')
    ->post('register', [UserController::class, 'store']);

Route::middleware('auth.api')->group(function () {
    Route::post('purchase', [UserController::class, 'purchase']);
    Route::get('check', [SubController::class, 'check']);
});

// mock api

Route::middleware(['auth.mock', 'throttle:mock'])->group(function () {
    Route::post('google', [SubController::class, 'verification']);
    Route::post('apple', [SubController::class, 'verification']);
});
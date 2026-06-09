<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('profile')->group(function(){
    Route::get('/', [UserController::class, 'show']);
    Route::patch('/update', [UserController::class, 'update']);
    Route::patch('/update/password', [UserController::class, 'updatePassword']);

    });
});

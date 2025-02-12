<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    
    Route::put('profile/update' , [UserController::class ,'update']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('delete-account' ,  [UserController::class , 'deleteAccount']);
});

Route::post('login', [AuthController::class, 'login']);


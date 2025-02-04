<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

// Auth
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    // Notification
    Route::controller(\App\Http\Controllers\API\NotificationController::class)->group(function () {
        Route::post('notification-token', 'updateNotificationToken')->name('notification-token');
        Route::get('/mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
        Route::get('/mark-as-read/{id}', 'markAsRead')->name('mark-as-read');
        Route::post('deleteAll', 'deleteAll')->name('deleteAll');
        Route::post('delete/{id}', 'delete')->name('delete');
    });



});

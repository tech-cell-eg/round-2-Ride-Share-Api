<?php

use App\Http\Controllers\API\VehicleController;
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
    Route::prefix('notifications')->controller(\App\Http\Controllers\API\NotificationController::class)->group(function () {
        Route::post('token', 'update')->name('notification-token');
        Route::get('', 'index')->name('notifications');
        Route::get('unread', 'unreadNotifications')->name('unread-notifications');
        Route::get('read', 'readNotifications')->name('read-notifications');
        Route::get('mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
        Route::get('mark-as-read/{id}', 'markAsRead')->name('mark-as-read');
        Route::delete('deleteAll', 'deleteAll')->name('deleteAll');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    // Location
    Route::post('/get-location', \App\Http\Controllers\API\CurrentLocationController::class);

    // Transports
    Route::get('transports', [\App\Http\Controllers\API\TransportController::class, 'index'])->name('transports');
    Route::get('available-cars', [VehicleController::class, 'availableCars'])->name('available-cars');
    Route::get('/car/{vehicle}', [VehicleController::class, 'showCar'])->name('car');

    //Rent request

});

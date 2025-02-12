<?php

use Illuminate\Support\Facades\Route;

Route::get('payment/success', function () {
    return 'success';
})->name('payment.success');

Route::get('payment/cancel', function () {
    return 'cancel';
})->name('payment.cancel');

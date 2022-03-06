<?php

use Controlpanel\Vouchers\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->name('controlpanel.')->prefix('controlpanel')->group(function () {
    Route::resource('vouchers', VoucherController::class);
});

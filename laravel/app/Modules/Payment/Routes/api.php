<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Payment\Controllers\SslCommerzPaymentController;

Route::middleware('auth:api')->group(function () {
    Route::prefix('payments')->group(function () {
        Route::post('initiate', [SslCommerzPaymentController::class, 'initiatePayment'])->name('api.payments.initiate');
        Route::post('initiate-ajax', [SslCommerzPaymentController::class, 'initiateAjaxPayment'])->name('api.payments.initiate.ajax');
        Route::post('success', [SslCommerzPaymentController::class, 'handleSuccess'])->name('api.payments.success');
        Route::post('fail', [SslCommerzPaymentController::class, 'handleFailure'])->name('api.payments.fail');
        Route::post('cancel', [SslCommerzPaymentController::class, 'handleCancel'])->name('api.payments.cancel');
    });
    
    Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
});

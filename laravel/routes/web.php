<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Payment\Controllers\SslCommerzPaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Payment routes
Route::prefix('payments')->group(function () {
    Route::get('checkout/easy', [SslCommerzPaymentController::class, 'exampleEasyCheckout'])->name('payments.checkout.easy');
    Route::get('checkout/hosted', [SslCommerzPaymentController::class, 'exampleHostedCheckout'])->name('payments.checkout.hosted');
    
    Route::post('initiate', [SslCommerzPaymentController::class, 'initiatePayment'])->name('payments.initiate');
    Route::post('initiate-ajax', [SslCommerzPaymentController::class, 'initiateAjaxPayment'])->name('payments.initiate.ajax');
    
    Route::post('success', [SslCommerzPaymentController::class, 'handleSuccess'])->name('payments.success');
    Route::post('fail', [SslCommerzPaymentController::class, 'handleFailure'])->name('payments.fail');
    Route::post('cancel', [SslCommerzPaymentController::class, 'handleCancel'])->name('payments.cancel');
    Route::post('ipn', [SslCommerzPaymentController::class, 'ipn'])->name('payments.ipn');
});

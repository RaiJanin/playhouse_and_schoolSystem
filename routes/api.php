<?php

use App\Http\Controllers\InformationsController;
use App\Http\Controllers\PlayHouseController;
use App\Http\Controllers\TurnstileController;
use App\Http\Controllers\MimoAdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes for json requests, get/submit requests only

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/submit/whole-form', [PlayHouseController::class, 'store']);
Route::post('/submit/make-otp', [PlayHouseController::class, 'makeOtp']);
Route::get('/check-phone/{phoneNum}', [PlayHouseController::class, 'checkPhone']);
Route::patch('/verify-otp/{phoneNum}', [PlayHouseController::class, 'verifyOTP']);
Route::delete('/delete-otp/{otpId}', [PlayHouseController::class, 'deleteOtp']);
Route::get('/search-returnee/{phoneNumber}', [PlayHouseController::class, 'searchReturnee'])->name('returnee.search');
Route::get('/get-orders', [PlayHouseController::class, 'getOrders']);
Route::get('/order-items/{id}', [PlayHouseController::class, 'getOrderItem']);
Route::patch('/order-items/{id}', [PlayHouseController::class, 'updateOrderItem']);
Route::patch('/check-out/{orderNum}', [PlayHouseController::class, 'checkOut']);
Route::post('/turnstile-srch', [TurnstileController::class, 'turnstileSrchPOST']);
Route::get('/get-contact', [InformationsController::class, 'getContact']);
Route::get('/get-inhouse', [MimoAdminController::class, 'monitoring']);

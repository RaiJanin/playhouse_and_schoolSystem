<?php

use App\Http\Controllers\PlayHouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//API routes for json requests, get/submit requests only

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/submit/whole-form', [PlayHouseController::class, 'store']);
Route::post('/submit/make-otp', [PlayHouseController::class, 'makeOtp']);
Route::patch('/verify-otp/{phoneNum}', [PlayHouseController::class, 'verifyOTP']);
Route::delete('/delete-otp/{otpId}', [PlayHouseController::class, 'deleteOtp']);
Route::get('/search-returnee/{phoneNumber}', [PlayHouseController::class, 'searchReturnee'])->name('returnee.search');

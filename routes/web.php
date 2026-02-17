<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayHouseController;

Route::get('/', function () {
    return view('pages.playhouse-landing');
})->name('playhouse.start');

Route::get('/registration', function () { 
    return view('pages.playhouse-registration'); 
})->name('playhouse.registration');

// Playhouse Controller Routes
Route::get('/playhouse/landing', [PlayHouseController::class, 'landing'])->name('playhouse.landing');
Route::get('/playhouse/registration', [PlayHouseController::class, 'registration'])->name('playhouse.registration');
Route::post('/search-returnee', [PlayHouseController::class, 'searchReturnee'])->name('returnee.search');
Route::post('/clear-session', [PlayHouseController::class, 'clearSession'])->name('session.clear');
Route::post('/playhouse/store', [PlayHouseController::class, 'store'])->name('playhouse.store');
Route::post('/playhouse/make-otp', [PlayHouseController::class, 'makeOtp'])->name('playhouse.makeOtp');
Route::post('/playhouse/verify-otp/{phoneNum}', [PlayHouseController::class, 'verifyOTP'])->name('playhouse.verifyOTP');
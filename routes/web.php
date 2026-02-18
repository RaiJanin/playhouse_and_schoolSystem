<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayHouseController;

Route::get('/', function () {
    return view('pages.playhouse-landing');
})->name('playhouse.start');

Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.registration');

//----- Version 2 UI
Route::prefix('v2')->group(function () {

    Route::get('/', function () { return view('v2.pages.playhouse-landing'); })->name('v2.playhouse.start');
    Route::get('/registration', function () { return view('v2.pages.playhouse-registration'); })->name('v2.playhouse.registration');

});


// Returnee search (POST)
Route::post('/playhouse/search-returnee', [PlayHouseController::class, 'searchReturnee'])->name('returnee.search');

// Registration page (with type parameter)
Route::get('/playhouse/registration', [PlayHouseController::class, 'registration'])->name('playhouse.registration');

// Clear session
Route::post('/playhouse/clear-session', [PlayHouseController::class, 'clearSession'])->name('playhouse.clear-session');

// Your existing store/OTP routes
Route::post('/playhouse/store', [PlayHouseController::class, 'store'])->name('playhouse.store');
Route::post('/playhouse/make-otp', [PlayHouseController::class, 'makeOtp'])->name('playhouse.make-otp');
Route::post('/playhouse/verify-otp/{phoneNum}', [PlayHouseController::class, 'verifyOTP'])->name('playhouse.verify-otp');
//----- mock ups
Route::get('/mockup-menu', function () {
    return view('pages.mockup.playhouse-registration');
});
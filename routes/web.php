<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('playhouse.registration');
// });

Route::get('/', function () {
    return redirect()->route('playhouse.phone');
});

Route::prefix('playhouse/registration')->group(function() {
    Route::get('/phone', function () { return view('pages.playhouse-registration'); })->name('playhouse.phone');
    Route::get('/otp', function () { return view('pages.playhouse-registration'); })->name('playhouse.otp');
    Route::get('/parent-info', function () { return view('pages.playhouse-registration'); })->name('playhouse.parent');
    Route::get('/children-info', function () { return view('pages.playhouse-registration'); })->name('playhouse.children');
    Route::get('/done', function () { return view('pages.playhouse-registration'); })->name('playhouse.done');
});

//----- reserved route for javascripted section management
Route::prefix('playhouse')->group(function() {
    Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.registration');
});

Route::get('/reference', function () {
    return view('reference.sample');
});
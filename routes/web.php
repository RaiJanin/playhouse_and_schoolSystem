<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('playhouse.registration');
// });

Route::get('/', function () {
    return view('pages.playhouse-landing');
});

Route::prefix('playhouse')->group(function() {
    Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.registration');
});

Route::get('/reference', function () {
    return view('reference.sample');
});

Route::get('/sample-2', function () {
    return view('reference.sample-2');
});
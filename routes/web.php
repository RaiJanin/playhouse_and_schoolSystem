<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;

Route::get('/', function () {
    return redirect()->route('playhouse.first');
});

Route::prefix('playhouse')->group(function() {
    Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.first');
    Route::get('/otp', function () { return view('pages.playhouse-otp'); })->name('playhouse.otp');
    Route::get('/parent-info', function () { return view('pages.playhouse-parent-info'); })->name('playhouse.parent');
});

Route::get('/reference', function () {
    return view('reference.sample');
});
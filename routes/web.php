<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.playhouse-landing');
})->name('playhouse.start');

Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.registration');

//----- Version 2 UI
Route::prefix('v2')->group(function () {

    Route::get('/', function () { return view('v2.pages.playhouse-landing'); })->name('v2.playhouse.start');
    Route::get('/registration', function () { return view('v2.pages.playhouse-registration'); })->name('v2.playhouse.registration');

});


//----- mock ups

Route::get('/mockup-menu', function () {
    return view('pages.mockup.playhouse-registration');
});
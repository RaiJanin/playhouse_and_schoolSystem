<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.playhouse-landing');
});

Route::get('/registration', function () { return view('pages.playhouse-registration'); })->name('playhouse.registration');
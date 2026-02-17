<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayHouseController;

Route::get('/', function () {
    return view('pages.playhouse-landing');
})->name('playhouse.start');

Route::get('/registration', [PlayHouseController::class, 'registration'])->name('playhouse.registration');


//----- mock ups

Route::get('/mockup-menu', function () {
    return view('pages.mockup.playhouse-registration');
});
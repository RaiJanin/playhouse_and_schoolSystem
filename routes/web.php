<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PlayHouseController;

//WEB routes for page viewing only

Route::get('/', function () {
    return view('pages.playhouse-landing');
})->name('playhouse.start');

Route::get('/check-in-source', [PlayHouseController::class, 'checkInSource'])->name('playhouse.checkin.source');
Route::get('/registration', [PlayHOuseController::class, 'registration'])->name('playhouse.registration');
Route::get('/order-info/{order_no}', [PlayHouseController::class, 'orderInfo'])->name('order.info');
Route::get('/checkout', [PlayHouseController::class, 'checkoutPage'])->name('playhouse.checkout');


//--------------TESTING

Route::get('/test-mail', function () {

    Mail::raw('This is a test email from Laravel.', function ($message) {
        $message->to('abrenicajanino03@gmail.com')
                ->subject('Laravel Mail Test');
    });

    return 'Email sent!';
});

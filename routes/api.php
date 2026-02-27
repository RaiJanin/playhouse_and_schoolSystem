<?php

use App\Http\Controllers\PlayHouseController;
//use App\Http\Controllers\FamilyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//API routes for json requests, get/submit requests only

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/submit/whole-form', [PlayHouseController::class, 'store']);
Route::post('/submit/make-otp', [PlayHouseController::class, 'makeOtp']);
Route::get('/check-phone/{phoneNum}', [PlayHouseController::class, 'checkPhone']);
Route::patch('/verify-otp/{phoneNum}', [PlayHouseController::class, 'verifyOTP']);
Route::delete('/delete-otp/{otpId}', [PlayHouseController::class, 'deleteOtp']);
Route::get('/search-returnee/{phoneNumber}', [PlayHouseController::class, 'searchReturnee'])->name('returnee.search');
Route::get('/get-orders', [PlayHouseController::class, 'getOrders']);
Route::patch('/check-out/{orderNum}', [PlayHouseController::class, 'checkOut']);

/**
 * Check out page
 * ------------------
 * APIs:
 * /api/get-orders - Get order data, search by guardian/parent name or phone number
 * /api/check-out/{order number} - Submit order by order number for check out
 * -------------------
 * 
 * -------------------
 * API Properties:
 * /api/get-orders-
 * ---Returns only one object, named 'orders' (Console the object to see all of the data)
 * 
 * /api/check-out/{order number}-
 * ---Returns a response
 *   + 'checked_out'
 *   + 'message'
 *   + 'extraCharge'   Extra charges, if nalapas ang bata sa gi check in nga hours (only display if has any amount)
 *   + 'order'   Order info object (you can use console to view data)
 * ---------------------
 * 
 * 
 * ---------------------
 * Page structure and flow:
 * 
 * use this web route - "/checkout" (or route name = route('playhouse.checkout')) for check out page
 * 
 * display two input fields, one for "Search using phone number, and "using guardian/parent name"
 * search button below
 * Use js to display all active orders
 * A view order button for each order
 * A Check out button below
 * 
 * Use the response message of check-out route (/api/check-out/{order number}) for message prompt pop
 * ----------------------
 */

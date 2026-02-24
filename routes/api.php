<?php

use App\Http\Controllers\PlayHouseController;
use App\Http\Controllers\FamilyController;
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

/*
|--------------------------------------------------------------------------
| Family Management API Routes (Parent, Guardian, Child)
| Database names match data names: 
|   - parent data -> parent_info table
|   - guardian data -> guardians table  
|   - child data -> children table
| Children are in a loop - one parent/guardian can have multiple children
|--------------------------------------------------------------------------
*/

// Parent (ParentInfo) routes - table: parent_info
Route::get('/parents', [FamilyController::class, 'getParents']);
Route::post('/parents', [FamilyController::class, 'createParent']);
Route::get('/parents/{id}', [FamilyController::class, 'getParent']);
Route::put('/parents/{id}', [FamilyController::class, 'updateParent']);
Route::delete('/parents/{id}', [FamilyController::class, 'deleteParent']);
Route::get('/parents/{id}/children', [FamilyController::class, 'getParentChildren']);

// Guardian routes - table: guardians
Route::get('/guardians', [FamilyController::class, 'getGuardians']);
Route::post('/guardians', [FamilyController::class, 'createGuardian']);
Route::get('/guardians/{id}', [FamilyController::class, 'getGuardian']);
Route::put('/guardians/{id}', [FamilyController::class, 'updateGuardian']);
Route::delete('/guardians/{id}', [FamilyController::class, 'deleteGuardian']);
Route::get('/guardians/{id}/children', [FamilyController::class, 'getGuardianChildren']);

// Child routes with loop support - table: children
Route::get('/children', [FamilyController::class, 'getChildren']);
Route::post('/children', [FamilyController::class, 'createChild']);
Route::get('/children/{id}', [FamilyController::class, 'getChild']);
Route::put('/children/{id}', [FamilyController::class, 'updateChild']);
Route::delete('/children/{id}', [FamilyController::class, 'deleteChild']);

// Link parent and guardian
Route::post('/link-parent-guardian', [FamilyController::class, 'linkParentGuardian']);

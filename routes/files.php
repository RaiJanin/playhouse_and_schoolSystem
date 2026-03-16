<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;

Route::prefix('admin/files')->group(function () {

    Route::get('/', [FileManagementController::class,'index']);
    Route::delete('/delete/{file}', [FileManagementController::class,'delete'])->where('file', '.*');

});
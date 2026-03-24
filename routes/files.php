<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;

Route::prefix('admin/files')->middleware('auth.basic.name')->group(function () {

    Route::get('/', [FileManagementController::class,'index'])->name('files.index');
    Route::delete('/delete/{file}', [FileManagementController::class,'delete'])->where('file', '.*');

});
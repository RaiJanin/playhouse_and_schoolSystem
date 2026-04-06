<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MimoAdminController;
use App\Http\Controllers\FileManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/admin-panel', function () {
    return view('pages.admin-panel.welcome');
});

Route::prefix('admin-panel')->group(function () {
    Route::get('/dashboard', [MimoAdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('/files')->middleware('auth')->group(function () {
        Route::get('/', [FileManagementController::class,'index'])->name('files.index');
        Route::delete('/delete/{file}', [FileManagementController::class,'delete'])->where('file', '.*');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MimoAdminController;
use App\Http\Controllers\FileManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/admin-panel', function () {
    return view('pages.admin-panel.welcome');
})->name('admin.panel');

Route::prefix('admin-panel')->middleware('auth')->group(function () {
    Route::get('/dashboard', [MimoAdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('/files')->group(function () {
        Route::get('/', [FileManagementController::class,'index'])->name('files.index');
        Route::delete('/delete/{file}', [FileManagementController::class,'delete'])->where('file', '.*');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/sms_blasts')->group(function () {
        Route::get('/', function () {
            return view('pages.admin-panel.sms-blast');
        })->name('sms_blast.index');
    });
});

Route::put('/admin/order-item/{selectedId}', [MimoAdminController::class, 'updateQr'])->name('order.updateQr');



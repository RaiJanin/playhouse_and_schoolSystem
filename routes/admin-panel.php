<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MimoAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/admin-panel', function () {
    return view('pages.admin-panel.welcome');
});

Route::prefix('admin-panel')->group(function () {
    Route::get('/dashboard', [MimoAdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';

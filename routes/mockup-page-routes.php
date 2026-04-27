<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin-panel')->middleware('auth')->group(function () {

    Route::get('/sms-blast', function () {
        return redirect()->route('sms-blast.index');
    });

    Route::prefix('/sms-blast')->group(function () {

        Route::get('/index', function () {
            return view('pages.admin-panel.sms-blast.mockup.index');
        })->name('sms-blast.index');

        Route::get('/details', function () {
            return view('pages.admin-panel.sms-blast.mockup.details');
        })->name('sms-blast.details');

        Route::get('/create', function () {
            return view('pages.admin-panel.sms-blast.mockup.create');
        })->name('sms-blast.create');

        Route::get('/sms-templates', function () {
            return view('pages.admin-panel.sms-blast.mockup.templates');
        })->name('sms-blast.templates');

    });
});
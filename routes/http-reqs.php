<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnstileController;
use App\Http\Controllers\RunCommandsViaHttp;

Route::prefix('/run-scheduler')->group(function () {
    Route::get('/reccured', [RunCommandsViaHttp::class, 'recurring']);
    Route::get('/scheduled', [RunCommandsViaHttp::class, 'scheduled']);
    Route::get('/time-based', [RunCommandsViaHttp::class, 'timeBased']);
});

Route::get('/turnstile/simulate-curl/params', [TurnstileController::class, 'curlRequest']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnstileController;
use App\Http\Controllers\RunCommandsViaHttp;

Route::get('/run-scheduler', [RunCommandsViaHttp::class, 'index']);
Route::get('/turnstile/simulate-curl/params', [TurnstileController::class, 'curlRequest']);

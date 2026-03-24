<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnstileController;
use App\Http\Controllers\RunCommandsViaController;

Route::get('/run-scheduler', [RunCommandsViaController::class, 'index']);
Route::get('/turnstile-flag', [TurnstileController::class, 'turnstileFlag']);

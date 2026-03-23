<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/run-scheduler', function () {

    if (request('key') !== env('SCHEDULER_KEY')) 
    {
        abort(403, 'Unauthorized');
    }

    $logs = [];
    $start = now();

    $logs[] = "Scheduler started at: " . $start;

    try {
        Artisan::call('app:check-timeouts');
        $logs[] = "✔ app:check-timeouts executed";

        $logs[] = Artisan::output();

        Artisan::call('otp:clean-expired');
        $logs[] = "✔ otp:clean-expired executed";

        $logs[] = Artisan::output();

    } catch (\Exception $e) {
        $logs[] = "❌ Error: " . $e->getMessage();
    }

    $end = now();
    $logs[] = "Finished at: " . $end;
    $logs[] = "Duration: " . $start->diffInSeconds($end) . " seconds";

    Log::info('Scheduler run', $logs);

    return response(
        "<pre>" . implode("\n", $logs) . "</pre>"
    );

});
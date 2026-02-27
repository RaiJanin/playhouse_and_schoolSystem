<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhoneNumber;
use Carbon\Carbon;

class CleanExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete or mark expired unverified OTPs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $now = Carbon::now();

        $deletedCount = PhoneNumber::where('is_verified', false)
            ->where('otp_expires_at', '<', $now)
            ->delete();

        $this->info("Expired OTPs cleaned: $deletedCount");
    }
}
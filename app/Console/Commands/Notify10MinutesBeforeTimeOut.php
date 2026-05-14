<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItems;
use App\Models\SmsBlast;
use App\Models\M06;
use Carbon\Carbon;
use App\Services\SmsBlastService;
use App\Services\SendSmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Notify10MinutesBeforeTimeOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:timeout-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic timeout reminder SMS';

    /**
     * Execute the console command.
     */
    public function handle(SmsBlastService $smsBlastService)
    {
        $blast = SmsBlast::getAutomatedBlast(SmsBlast::SLUG_TIMEOUT);
        if (!$blast)
        {
            $this->error('Timeout reminder blast not found.');

            return Command::FAILURE;
        }

        $items = $this->querySessions();
        if (!$items)
        {
            return Command::SUCCESS;
        }

        $recipientIds = [];

        foreach ($items as $item)
        {
            $parent = $item->child->parent;

            if (!$parent || !$parent->mobileno) {
                continue;
            }

            $recipientIds[] = $parent->d_code;
        }

        $recipientIds = array_unique($recipientIds);

        if (empty($recipientIds))
        {
            $this->info('No valid recipients.');

            return Command::SUCCESS;
        }

        $result = $smsBlastService->sendBlast(
            $blast,
            $recipientIds
        );

        OrderItems::whereIn('id', $items->pluck('id'))
            ->update([
                'notified_timeout' => true
            ]);

        $this->info("Timeout reminders processed.");
        $this->info("Sent: {$result['sent']}");
        $this->info("Failed: {$result['failed']}");

        return Command::SUCCESS;
    }

    private function querySessions()
    {
        $now = Carbon::now();

        $items = OrderItems::with(['child.parent'])
            ->where(function ($query) use ($now) {
                $query->whereRaw(
                    "ckin + (durationhours * interval '1 hour') BETWEEN ? AND ?",
                    [
                        $now,
                        $now->copy()->addMinutes(10)
                    ]
                );
            })
            ->where('checked_out', false)
            ->where('notified_timeout', false)
            ->where('durationhours', '!=', 5)
            ->get();

        if ($items->isEmpty())
        {
            $this->info('No timeout reminders.');
            return [];
        }

        return $items;
    }

}

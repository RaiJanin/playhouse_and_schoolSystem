<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItems;
use App\Models\M06;
use Carbon\Carbon;
use App\Services\SendSmsService;

class Notify10MinutesBeforeTimeOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-timeouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify before 10 mins of time out';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $items = OrderItems::with(['child.guardians' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'child.parent'])
            ->where(function ($query) use ($now) {
                $query->whereRaw(
                    "created_at + (durationhours * interval '1 hour') BETWEEN ? AND ?",
                    [$now->copy()->addMinutes(9), $now->copy()->addMinutes(10)]
                );
            })
            ->where('checked_out', false)
            ->where('notified_timeout', false)
            ->get();

        $notifications = [];

        foreach ($items as $item) 
        {
            $parent = $item->child->updatedby;
            if (!$parent) continue;

            if (!isset($notifications[$parent])) 
            {
                $notifications[$parent] = [];
            }

            $notifications[$parent][] = $item->child;
        }

        foreach ($notifications as $parent => $children) 
        {
            $childrenNames = [];
            $guardianMap = [];
            $phonenum = M06::where('d_name', $parent)->pluck('mobileno')->first();

            foreach ($children as $child) 
            {
                $childrenNames[] = $child->firstname;

                $latestGuardian = $child->guardians->first();
                if ($latestGuardian && $latestGuardian->guardianauthorized) 
                {
                    $guardianMap[$latestGuardian->d_name][] = $child->firstname;
                }
            }

            $childrenString = implode(', ', $childrenNames);

            $guardianMessages = [];
            foreach ($guardianMap as $guardianName => $childNames) 
            {
                $childList = implode(', ', $childNames);
                $guardianMessages[] = "{$guardianName} can pick up {$childList}";
            }

            $guardianString = implode("\n", $guardianMessages);

            $message = "NOTICE FROM MIMO PLAY CAFE\n\n{$parent}, your children: {$childrenString} are about to timeout.";
            if (!empty($guardianString)) 
            {
                $message .= "\n\n{$guardianString}";
            }

            $message = mb_convert_encoding($message, 'ASCII', 'UTF-8');
            $sendNotifications = OrderItems::whereIn('id', $items->pluck('id'))->update(['notified_timeout' => true]);
            
            $this->sendNotification($message, $phonenum);
            $this->info("Sent notifications: {$sendNotifications}");
            
        }
        return 0;
    }

    private function sendNotification($msg, $recepientNum)
    {
        $this->info($msg);
        $response = SendSmsService::sendnowsms($recepientNum, $msg);
        $this->info("SMS response: {$response['response']}");
        return true;
    }
}

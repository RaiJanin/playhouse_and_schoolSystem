<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderItems;
use Carbon\Carbon;

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
            }])
            ->where(function ($query) use ($now) {
                $query->whereRaw(
                    "created_at + (durationhours * interval '1 hour') BETWEEN ? AND ?",
                    [$now->copy()->addMinutes(9), $now->copy()->addMinutes(10)]
                )
                ->orWhereRaw(
                    "created_at + (durationhours * interval '1 hour') < ?",
                    [$now]
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

            //OrderItems::whereIn('id', $items->pluck('id'))->update(['notified_timeout' => true]);
            $this->sendNotification($message);
        }

        return 0;
    }

    private function sendNotification($msg)
    {
        $this->info($msg);
    }
}

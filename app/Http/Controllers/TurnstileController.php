<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItems;
use Carbon\Carbon;

class TurnstileController extends Controller
{
    public function turnstileFlag(Request $request)
    {
        $orderLineID = $request->query('ordlne_id');
        $orderCode = $request->query('ord_code');
        $playhouseCheckType = $request->query('plchk_type');
        $isFreeze = $request->query('freeze');
        $qrChild = $request->query('qrc');
        $qrGuardian = $request->query('qrg');
        $timeStamp = $request->query('time') ? Carbon::parse($request->query('time')) : now();

        $orderItem = OrderItems::where('id', $orderLineID)->where('ord_code_ph', $orderCode)->first();

        if(!$orderItem)
        {
            return response("<pre>No Order Item Found </pre>");
        }

        if(!$qrChild || !$qrGuardian)
        {
            return response("<pre>QR Codes must be filled </pre>");
        }

        switch($playhouseCheckType)
        {
            case 'ckin':
                $orderItem->ckin = $timeStamp;
                break;
            case 'bkout':
                if(!$isFreeze)
                {
                    return response("<pre>Freeze option must be enabled</pre>");
                }
                $orderItem->bkout = $timeStamp;
                break;
            case 'bkin':
                if($isFreeze)
                {
                    return response("<pre>Freeze option must be disabled</pre>");
                }
                $orderItem->bkin = $timeStamp;
                break;
            default:
                return response("<pre> Type is incorrect </pre>");
        }

        $orderItem->isfreeze = $isFreeze;
        $orderItem->qr_child = $qrChild;
        $orderItem->qr_guardian = $qrGuardian;
        $orderItem->save();

       return response("<pre>Updated successfully.</pre>");
    }

    public function turnstileSrch(Request $request)
    {
        $status = $request->query('status');
        $qrCode = $request->query('qr');
        $time = $request->query('time') ? Carbon::parse($request->query('time')) : now();

        try
        {
            DB::beginTransaction();
            $response = [];

            if(!$status)
            {
                return response("<pre>Status is required with values 'in' or 'out'</pre>");
            }
            if(!$qrCode)
            {
                return response("<pre>QRCode is required</pre>");
            }

            $orderItems = OrderItems::where(function ($query) use ($qrCode) {
                $query->where('qr_child', $qrCode)
                    ->orWhere('qr_guardian', $qrCode);
                })->where('checked_out', false)->get();

            if(!$orderItems)
            {
                return response("<pre>No reservations found or invalid qr code</pre>");
            }

            foreach($orderItems as $orderItem)
            {
                $action = 'none';

                switch($status)
                {
                    case 'in':
                        if(!$orderItem->ckin && !$orderItem->bkout && !$orderItem->bkin)
                        {
                            $orderItem->ckin = $time;
                            $orderItem->isfreeze = false;
                            $action = "<pre>Checked-in</pre>";
                        } 
                        else
                        if($orderItem->ckin && $orderItem->bkout && !$orderItem->bkin)
                        {
                            $orderItem->bkin = $time;
                            $orderItem->isfreeze = false;
                            $action = "<pre>Resume from freeze</pre>";
                        }
                        else
                        {
                            $action = "<pre>Ignored(already active)</pre>";
                        }
                        break;
                    case 'out':
                        if($orderItem->ckin && !$orderItem->bkout)
                        {
                            $orderItem->bkout = $time;
                            $orderItem->isfreeze = true;
                            $action = "<pre>Frozen</pre>";
                        }
                        else
                        {
                            $action = "<pre>Ignored(cannot freeze)</pre>";
                        }
                        break;
                    default:
                        return response("<pre> Status value is incorrect </pre>");
                }

                $orderItem->save();

                $response[] = [
                    'order_item_id' => $orderItem->id,
                    'qr_child' => $orderItem->qr_child,
                    'qr_guardian' => $orderItem->qr_guardian,
                    'action' => $action,
                    'timestamp' => $time->toDateTimeString(),
                ];

            }

            DB::commit();

            return response("<pre>Processed Successfully\n" . implode("\n", array_map(function ($item) {
                return "OrderItem #{$item['order_item_id']} | Child: {$item['qr_child']} | Guardian: {$item['qr_guardian']} | Action: {$item['action']} | Time: {$item['timestamp']}";
            }, $response)) . "</pre>");

        } catch(\Exception $e) {
            DB::rollback();

            return response("<pre>". $e->getMessage() ."</pre>");
        }
        
    }
}

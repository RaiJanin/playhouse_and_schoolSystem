<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItems;
use App\Services\SendSmsService;
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

    public function turnstileSrchPOST(Request $request)
    {
        $request->validate([
            'qr' => 'required|string|max:20',
            'status' => 'required|string|max:10',
            'time' => 'nullable|date'
        ]);

        $qrCode = $request->qr;
        $status = $request->status;
        $time = $request->time ? Carbon::parse($request->time) : now();

        try
        {
            DB::beginTransaction();
            $response = [];
            $hasSuccess = false;
            $validActions = [];

            if(!$status)
            {
                return response()->json([
                    'message' => "Status is required with values 'in' or 'out'"
                ]);
            }
            if(!$qrCode)
            {
                return response()->json([
                    'message' => 'QRCode is required'
                ]);
            }

            $orderItems = OrderItems::with('child')->where(function ($query) use ($qrCode) {
                $query->where('qr_child', $qrCode)
                    ->orWhere('qr_guardian', $qrCode);
                })->where('checked_out', false)->get();

            if(!$orderItems)
            {
                return response()->json([
                    'message' => 'No reservations found or invalid qr code'
                ]);
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
                        return response()->json([
                            'message' => 'Status value is incorrect'
                        ]);
                }

                $orderItem->save();

                $response[] = [
                    'order_item_id' => $orderItem->id,
                    'qr_child' => $orderItem->qr_child,
                    'qr_guardian' => $orderItem->qr_guardian,
                    'action' => $action,
                    'timestamp' => $time->toDateTimeString(),
                ];

                $cleanAction = strip_tags($action);

                if ($cleanAction !== "Ignored(already active)" && $cleanAction !== "Ignored(cannot freeze)") {
                    $hasSuccess = true;

                    $childName = $orderItem->child->firstname ?? 'Child';

                    $validActions[] = "{$childName} - {$cleanAction} (" . $time->format('h:i A') . ")";
                }

            }

            DB::commit();

            $message = "Notice from Mimo Web\n\n";
            if ($hasSuccess && count($validActions) > 0) {
                $message .= "Here are the latest updates:\n\n";
                $message .= implode("\n", $validActions);

                SendSmsService::sendnowsms('9228480788', $message);
                SendSmsService::sendnowsms('9158060792', $message);
            }

            return response()->json([
                'message' => 'Processed Successfully',
                'processedDatas' => array_map(function ($item) {
                    return [
                        'order_item_id' => $item['order_item_id'],
                        'child' => $item['qr_child'],
                        'guardian' => $item['qr_guardian'],
                        'action' => $item['action'],
                        'timestamp' => $item['timestamp'],
                    ];
                }, $response),
            ]);

        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
    }
}

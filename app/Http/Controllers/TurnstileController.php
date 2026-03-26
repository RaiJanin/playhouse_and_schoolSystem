<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\OrderItems;
use App\Services\SendSmsService;
use Carbon\Carbon;

class TurnstileController extends Controller
{

    public function turnstileSrchPOST(Request $request)
    {
        $request->validate([
            'qr' => 'required|string|max:20',
            'status' => 'required|string|max:10',
            'time' => 'nullable|date'
        ]);

        $qrCode = $request->qr;
        $status = strtolower($request->status); 
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
                    case 'in' || 'entrance':
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
                    case 'out' || 'exit':
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
                    'qr_child' => $orderItem->qr_child . ': ' . $orderItem->child->firstname.' '.$orderItem->child->lastname,
                    'action' => $action,
                    'timestamp' => $time->toDateTimeString(),
                ];

                $cleanAction = strip_tags($action);

                if ($action !== "<pre>Ignored(already active)</pre>" && $action !== "<pre>Ignored(cannot freeze)</pre>") {
                    $hasSuccess = true;

                    $childName = $orderItem->child->firstname.' '.$orderItem->child->lastname ?? '';

                    $validActions[] = "{$childName} - {$cleanAction} (" . $time->format('h:i A') . ")";
                }

            }

            DB::commit();

            $message = "NOTICE FROM CLOUD MIMO\n\n";
            if ($hasSuccess && count($validActions) > 0) {
                $message .= "Here are the latest updates:\n\n";
                $message .= implode("\n", $validActions);

                SendSmsService::sendnowsms('09228480788', $message); //Sir noei's
                SendSmsService::sendnowsms('09158060792', $message); //sir paul's
            }

            return response()->json([
                'message' => 'Processed Successfully',
                'processedDatas' => array_map(function ($item) {
                    return [
                        'order_item_id' => $item['order_item_id'],
                        'child' => $item['qr_child'],
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

    public function curlRequest(Request $request)
    {
        $status = $request->query('status');
        $qrCode = $request->query('qr');
        $time = $request->query('time');

        if(!$status && !$qrCode)
        {
            return response("<pre>Invalid fields</pre>");
        }

        $data = [
            'status' => $status,
            'qr' => $qrCode,
            'time' => $time ?? null
        ];

        $jsonData = json_encode($data);

        $url = env('APP_URL') . "api/turnstile-srch"; 

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            echo "Response: " . $response;
        }

        curl_close($ch);
    }
}

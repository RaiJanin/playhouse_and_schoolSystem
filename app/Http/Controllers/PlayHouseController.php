<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\PhoneNumber;
use App\Models\M06;
use App\Models\M06Guardian;
use App\Models\M06Child;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Market;
use App\Models\DurationPrices;
use App\Models\ItemsPrices;
use App\Services\DecodeBase64File;
use App\Http\Resources\M06Resource;
use App\Services\SendSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

class PlayHouseController extends Controller
{
    public function registration()
    {
        $durations = DurationPrices::all();
        $items = ItemsPrices::pluck('price', 'item');

        return view('pages.playhouse-registration', compact('durations', 'items')); 
    }

    public function checkInSource()
    {
        $data = Market::getAllMarket();

        return view('pages.playhouse-checkin-source', compact('data'));
    }

    public function checkoutPage()
    {
        $durations = DurationPrices::all();
        $items = ItemsPrices::pluck('price', 'item');

        return view('pages.playhouse-checkout', compact('durations', 'items')); 
    }

    public function store(StorePlayhouseFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $parentAsGuardian = '1';

            foreach ($data['child'] as $child) {
                if (!empty($child['guardianName']) || !empty($child['guardianLastName'])) {
                    $parentAsGuardian = '0';
                    break;
                }
            }

            $parsedPhone = $this->formatPhone09($data['phone']);

            $parent = M06::updateOrCreate(['mobileno' => $parsedPhone],[
                'd_name' => $data['parentName'] . ' ' . $data['parentLastName'],
                'mkt_code' => $data['mkt_code'] ?? null,
                'firstname' => $data['parentName'],
                'lastname' => $data['parentLastName'],
                'birthday' => $data['parentBirthday'],
                'mobileno' => $parsedPhone,
                'email' => $data['parentEmail'],
                'isparent' => true,
                'isguardian' => $parentAsGuardian,
                'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
            ]);

            $totalPrice = 0;
            $durationPrices = DurationPrices::pluck('price', 'duration_hour');
            $socksPrice = ItemsPrices::where('item', 'socks_price')->first();

            if($request->has('child'))
            {
                foreach($data['child'] as $child) 
                {
                    $childM = M06Child::updateOrCreate(
                        [
                            'd_code' => $parent->d_code,
                            'firstname' => $child['name'],
                            'birthday' => $child['birthday'],
                        ],
                        [
                            'lastname' => $parent->lastname,
                            'age' => Carbon::parse($child['birthday'])->age,
                            'createdby' => $parent->d_name,
                            'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
                        ]
                    );

                    $photoPath = null;
                    $filename = 'child_' . $childM->d_code_c . '_';
                    $folder = 'children_photos';

                    if (!empty($child['photo']) && !$childM->photo &&$childM) 
                    {
                        $photoPath = DecodeBase64File::makeFile($child['photo'], $folder, $filename);
                        $childM->photo = $photoPath;
                        $childM->save();
                    }

                    if($child['guardianName'] || $child['guardianLastName'])
                    {
                        $guardianFullname = $child['guardianName'] . ' ' . $child['guardianLastName'] ?? null;
                
                        M06Guardian::updateOrCreate(
                            [
                                'd_code' => $parent->d_code,
                                'd_code_c' => $childM->d_code_c,
                            ],
                            [
                                'd_code' => $parent->d_code,
                                'd_code_c' => $childM->d_code_c,
                                'd_name' => $guardianFullname,
                                'firstname' => $child['guardianName'],
                                'lastname' => $child['guardianLastName'] ?? null,
                                'age' => $child['guardianAge'] ?? null,
                                'mobileno' => $child['guardianPhone'] ?? null,
                                'isparent' => false,
                                'isguardian' => true,
                                'guardianauthorized' => $child['guardianAuthorized'],
                                'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                                'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
                            ]
                        );
                    }

                    $childprice = ($durationPrices[$child['playDuration']] ?? 0) + ($child['addSocks'] * $socksPrice->price);
                    $totalPrice += $childprice;
                }
            }

            $fbProfileUrl = $data['fb_pp_url'] ?? null;

            $order = Orders::create([
                'parent' => $parent->d_name,
                'mkt_code' => $data['mkt_code'],
                'd_code' => $parent->d_code,
                'total_amnt' => $totalPrice,
                'fb_pp_url' => $fbProfileUrl
            ]);

            if(is_array($data['child']) && $request->has('child'))
            {
                foreach($data['child'] as $child) {
                    $childModel = M06Child::where('firstname', $child['name'])
                                    ->where('birthday', $child['birthday'])
                                    ->first();

                    $duration = $child['playDuration'] === 'unlimited' ? '5' : $child['playDuration'];

                    $grdFullName = trim(($child['guardianName'] ?? '') . ' ' . ($child['guardianLastName'] ?? ''));

                    OrderItems::create([
                        'ord_code_ph' => $order->ord_code_ph,
                        'd_code_child' => $childModel->d_code_c,
                        'guardian' => $grdFullName ?: null,
                        'durationhours' => $duration,
                        'durationsubtotal' => $durationPrices[$child['playDuration']] ?? 0,
                        'socksqty' => $child['addSocks'],
                        'socksprice' => $child['addSocks'] * $socksPrice->price,
                        'subtotal' => ($durationPrices[$child['playDuration']] ?? 0) + ($child['addSocks'] * $socksPrice->price),
                        'disc_code' => $data['discountCode']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'isFormSubmitted' => true,
                'orderNum' => $order->ord_code_ph
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'isFormSubmitted' => false,
                'dataRequests' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
        
    }

    public function makeOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|max:20',
                'email' => 'nullable|string|max:50'
            ]);

            $OTP = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $phone = $request->phone;

            $phoneRecord = PhoneNumber::create([
                'phone_number' => $this->formatPhone09($request->phone),
                'email' => $request->email ?? null,
                'otp_code' => $OTP,
                'otp_expires_at' => Carbon::now()->addMinutes(5)
            ]);
            
            if($phoneRecord)
            {
                $message = 'JDEN SMS: Your OTP code is '.$OTP.', It is valid for 5 minutes, dont share your code with anyone, thank you.';
                $smsStatus = SendSmsService::sendnowsms($phone,$message);

                if($request->filled('email'))
                {
                    Mail::to($request->email)->queue(new SendOtpMail($OTP));
                }
                
                if(!$smsStatus['success'])
                {
                    return response()->json([
                        'generated' => true,
                        'id' => $phoneRecord->id,
                        'code' => $OTP,
                        'isSent' => false,
                        'smsStatus' => $smsStatus['status'],
                        'smsResponse' => $smsStatus['response']
                    ]);
                }
            }

            return response()->json([
                'generated' => true,
                'id' => $phoneRecord->id,
                'code' => $OTP,
                'isSent' => true,
                'smsStatus' => $smsStatus['response']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'generated' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function verifyOTP(Request $request, $phoneNum)
    {
        $parsedPhone = $this->formatPhone09($phoneNum);
        try {
            $request->validate(['otp' => 'required|string|size:3']);

            $phoneVerified = PhoneNumber::where('phone_number', $parsedPhone)
                                    ->where('otp_code', $request->otp)
                                    ->whereNull('otp_verified_at')
                                    ->where('otp_expires_at', '>', Carbon::now())
                                    ->first();

            if(!$phoneVerified) 
            {
                return response()->json([
                    'isCorrectOtp' => false,
                ]);
            }

            $phoneVerified->update([
                'is_verified' => true,
                'otp_verified_at' => Carbon::now()
            ]);
            
            $oldUserData = M06::where('mobileno', $parsedPhone)->first();

            if(!$oldUserData)
            {
                return response()->json([
                    'isCorrectOtp' => true,
                    'isOldUser' => false,
                    'phoneNum' => $parsedPhone,
                ]);
            }

            return response()->json([
                'isCorrectOtp' => true,
                'isOldUser' => true,
                'phoneNum' => $parsedPhone,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'isCorrectOtp' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteOtp($otpId)
    {
        $OtpToDelete = PhoneNumber::find($otpId);
                            
        if(!$OtpToDelete)
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to find phone and OTP',
            ]);
        }
        $OtpToDelete->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function searchReturnee($phoneNumber)
    {
        $parsedPhone = $this->formatPhone09($phoneNumber);

        $oldUserData = M06::with(['children.guardians'])
                        ->where('mobileno', $parsedPhone)
                        ->where('isparent', true)
                        ->first();

        foreach ($oldUserData->children as $child) {
            $child->makeVisible('photo');
        }
        
        return response()->json([
            'oldUserData' => new M06Resource($oldUserData),
            'userLoaded' => true,
        ]);
    }

    public function orderInfo($orderNo)
    {
        $order = Orders::with(['parentPl', 'orderItems'])->where('ord_code_ph', $orderNo)->first();

        $order->orderItems->each(function ($item) {
            $item->child = M06Child::find($item->d_code_child);
        });

        return view('pages.order-info', compact('order'));
    }

    public function getOrders(Request $request)
    {
        $phoneNum = $request->query('ph_num') ?? null;
        $guardian = $request->query('grdian_name') ?? null;
        $orderCode = $request->query('ord_code') ?? null;
        
        $query = Orders::query();
        $d_code_query = null;

        if($phoneNum)
        {
            $parsedPhone = $this->formatPhone09($phoneNum);
            $getRecordUsingMobile = M06::where('mobileno', $parsedPhone)->first();

            if (!$getRecordUsingMobile)
            {
                return response()->json([
                    'orders' => [],
                    'message' => 'Phone number not found in our records.',
                    'not_found' => true
                ]);
            }

            $d_code_query = $getRecordUsingMobile->d_code;
        }

        if($guardian)
        {
            $isParent = M06::where('d_name', $guardian)->where('isparent', true)->first();
            $getparent = $isParent;

            if(!$isParent)
            {
                $query->where('guardian', $guardian);
            }
            else
            {
                $d_code_query = $getparent->d_code;
            }

        }

        if($orderCode)
        {
            $query->where('ord_code_ph', $orderCode);
        }

        if($d_code_query)
        {
            $query->where('d_code', $d_code_query);
        }

        $orderToCheckout = $query->whereHas('orderItems', function($qu) {
                $qu->where('checked_out', false);
            })->with(['orderItems' => function($item) {
                $item->with('child')->where('checked_out', false);
            }])->get();

        return response()->json([
            'orders' => $orderToCheckout
        ]);
    }

    public function checkOut($orderItemId)
    {
        try 
        {
            DB::beginTransaction();

            $items = ItemsPrices::pluck('price', 'item');

            $orderItem = OrderItems::with('order')
                ->where('id', $orderItemId)
                ->first();

            if (!$orderItem) 
            {
                return response()->json([
                    'checked_out' => false,
                    'message' => 'Order item not found'
                ]);
            }

            if ($orderItem->checked_out) 
            {
                return response()->json([
                    'checked_out' => false,
                    'message' => 'This child is already checked out'
                ]);
            }

            // time computation
            $checkIn = Carbon::parse($orderItem->created_at);
            $checkOut = Carbon::now();

            $paidMinutes = $orderItem->durationhours * 60;
            $actualMinutes = $checkIn->diffInMinutes($checkOut);

            $maxMinutes = 5 * 60;
            if($actualMinutes > $maxMinutes)
            {
                $actualMinutes = $maxMinutes;
            }

            $extraCharge = 0;

            if (($actualMinutes > $paidMinutes) && ($orderItem->durationhours !== 5)) 
            {
                $extraMinutes = $actualMinutes - $paidMinutes;
                $chargeUnits = ceil($extraMinutes / $items['minutes_per_charge']);
                $extraCharge = $items['charge_of_minutes'] * $chargeUnits;

                $orderItem->lne_xtra_chrg = $extraCharge;
            }

            $orderItem->checked_out = true;
            $orderItem->save();

            // update parent order totals
            $order = $orderItem->order;
            $currentTotal = $order->total_amnt;

            $order->xtra_chrg_amnt += $extraCharge;
            $order->total_amnt = $orderItem->lne_xtra_chrg + $currentTotal;
            $order->save();

            DB::commit();

            return response()->json([
                'checked_out' => true,
                'message' => 'Child checked out successfully',
                'extraCharge' => $extraCharge,
                'holdingTotal' => $currentTotal,
                'orderItem' => $orderItem->load('child'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    

    private function formatPhone09($phonenum)
    {
        $phoneInput = preg_replace('/[^0-9]/', '', $phonenum);
        $finalNum = $phoneInput;
        if(substr($phoneInput, 0, 2) === '63')
        {
            $finalNum = '0' . substr($phoneInput, 2);
        }
        if (strlen($phoneInput) === 10 && substr($phoneInput, 0, 1) === '9') 
        {
            $finalNum = '0' . $phoneInput;
        }

        return $finalNum;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\PhoneNumber;
use App\Models\M06;
use App\Models\M06Child;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Services\DecodeBase64File;
use App\Http\Resources\M06Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PlayHouseController extends Controller
{
    public function landing()
    {
        return view('pages.playhouse-landing');
    }

    public function store(StorePlayhouseFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $parentAsGuardian = !$request->filled('guardianName') ? '1' : '0';

            $parent = M06::updateOrCreate(['mobileno' => $data['phone']],[
                'd_name' => $data['parentName'] . ' ' . $data['parentLastName'],
                'firstname' => $data['parentName'],
                'lastname' => $data['parentLastName'],
                'birthday' => $data['parentBirthday'],
                'mobileno' => $data['phone'],
                'email' => $data['parentEmail'],
                'isparent' => true,
                'isguardian' => $parentAsGuardian,
                'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
            ]);


            if($request->filled('guardianName'))
            {
                $guardianFullname = $data['guardianName'] . ' ' . $data['guardianLastName'];
                
                M06::updateOrCreate(
                    [
                        'mobileno' => $data['guardianPhone'],
                        'd_name' => $guardianFullname,
                        'isguardian' => true,
                        'updatedby' => $parent->d_name
                    ],
                    [
                        'd_name' => $guardianFullname,
                        'firstname' => $data['guardianName'],
                        'lastname' => $data['guardianLastName'],
                        'mobileno' => $data['guardianPhone'],
                        'isparent' => false,
                        'isguardian' => true,
                        'guardianauthorized' => $data['guardianAuthorized'],
                        'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                        'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
                    ]
                );
                
            }

            $totalPrice = 0;
            $pricePerDuration = 100;
            $socksPrice = 100;

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
                    $folder = 'children_photos/';

                    if (!empty($child['photo']) && !$childM->photo &&$childM) 
                    {
                        $photoPath = DecodeBase64File::makeFile($child['photo'], $folder, $filename);
                        $childM->photo = $photoPath;
                        $childM->save();
                    }

                    $duration = $child['playDuration'] === 'unlimited' ? '5' : $child['playDuration'];
                    $childprice = ($duration * $pricePerDuration) + ($child['addSocks'] * $socksPrice);
                    $totalPrice += $childprice;
                }
            }

            $order = Orders::create([
                'guardian' => $parent->d_name,
                'd_code' => $parent->d_code,
                'total_amnt' => $totalPrice,
            ]);

            if(is_array($data['child']) && $request->has('child'))
            {
                foreach($data['child'] as $child) {
                    $childModel = M06Child::where('firstname', $child['name'])
                                    ->where('birthday', $child['birthday'])
                                    ->first();

                    $duration = $child['playDuration'] === 'unlimited' ? '5' : $child['playDuration'];

                    OrderItems::create([
                        'ord_code_ph' => $order->ord_code_ph,
                        'd_code_child' => $childModel->d_code_c,
                        'durationhours' => $duration,
                        'durationsubtotal' => $duration * $pricePerDuration,
                        'socksqty' => $child['addSocks'],
                        'socksprice' => $child['addSocks'] * $socksPrice,
                        'subtotal' => ($duration * $pricePerDuration) + ($child['addSocks'] * $socksPrice),
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
            $request->validate(['phone' => 'required|string|max:20']);

            $OTP = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $phone = $request->phone;

            $phoneRecord = PhoneNumber::create([
                'phone_number' => $phone,
                'otp_code' => $OTP,
                'otp_expires_at' => Carbon::now()->addMinutes(5)
            ]);

            return response()->json([
                'generated' => true,
                'id' => $phoneRecord->id,
                'code' => $OTP
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

        $request->validate(['otp' => 'required|string|size:3']);

        $phoneVerified = PhoneNumber::where('phone_number', $phoneNum)
                                ->where('otp_code', $request->otp)
                                ->where('is_verified', false)
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
        
        $oldUserData = M06::where('mobileno', $phoneNum)->first();

        if(!$oldUserData)
        {
            return response()->json([
                'isCorrectOtp' => true,
                'isOldUser' => false,
                'phoneNum' => $phoneNum,
            ]);
        }

        return response()->json([
            'isCorrectOtp' => true,
            'isOldUser' => true,
            'phoneNum' => $phoneNum,
        ]);
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
        $oldUserData = M06::with(['children', 'guardians'])
                        ->where('mobileno', $phoneNumber)
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

        return view('v2.pages.order-info', compact('order'));
    }

    public function getOrders(Request $request)
    {
        $phoneNum = $request->query('ph_num') ?? null;
        $guardian = $request->query('grdian_name') ?? null;

        if($phoneNum)
        {
            $getRecordUsingMobile = M06::where('mobileno', $phoneNum)->first();

            $orderToCheckout = Orders::where('d_code', $getRecordUsingMobile->d_code)
                    ->whereHas('orderItems', function($query) {
                        $query->where('checked_out', false);
                    })
                    ->with(['orderItems' => function($item) {
                        $item->where('checked_out', false);
                    }])
                    ->get();

            return response()->json([
                'orders' => $orderToCheckout
            ]);
        }

        if($guardian)
        {
            $isParent = M06::where('d_name', $guardian)->where('isparent', true)->first();
            $getparent = $isParent->d_code;

            if(!$isParent)
            {
                $getRecordUsingGuardian = M06::where('d_name', $guardian)->get();
                $updatedByValues = $getRecordUsingGuardian->pluck('updatedby')->unique();
                $getparent = M06::whereIn('updatedby', $updatedByValues)
                    ->where('isparent', true)
                    ->first();
            }
            
            $orderToCheckout = Orders::where('d_code', $getparent)
                    ->whereHas('orderItems', function($query) {
                        $query->where('checked_out', false);
                    })
                    ->with(['orderItems' => function($item) {
                        $item->where('checked_out', false);
                    }])
                    ->get();

            return response()->json([
                'orders' => $orderToCheckout
            ]);
        }
    }

    public function checkOut($orderNo)
    {
        try
        {
            DB::beginTransaction();

            $orderCheckingOut = Orders::with(['orderItems'])
                                ->where('ord_code_ph', $orderNo)
                                ->first();

            if(!$orderCheckingOut)
            {
                return response()->json([
                    'checked_out' => false,
                    'message' => 'Order not found'
                ]);
            }

            $hasActiveItems = $orderCheckingOut->orderItems->where('checked_out', true)->isNotEmpty();

            if($hasActiveItems)
            {
                return response()->json([
                    'checked_out' => false,
                    'message' => 'Order already checked out'
                ]);
            }

            $extraTotal = 0;
            foreach($orderCheckingOut->orderItems as $orderItem)
            {
                // check if the check out time is under or equals the check in duration, if it is not, charge 20 for each 2 minutes

                $checkIn = Carbon::parse($orderItem->created_at);
                $checkOut = Carbon::now();
                $paidHours = $orderItem->durationhours * 60;
                $actualMinutes = $checkIn->diffInMinutes($checkOut);

                if ($actualMinutes > $paidHours) 
                {

                    $extraMinutes = $actualMinutes - $paidHours;

                    $chargeUnits = ceil($extraMinutes / 2);
                    $extraCharge = $chargeUnits * 20;

                    $orderItem->lne_xtra_chrg = $extraCharge;
                    $orderItem->checked_out = true;

                    $extraTotal += $extraCharge;
                }
                else
                {
                    $orderItem->checked_out = true;
                }
                $orderItem->save();
            }

            $orderCheckingOut->xtra_chrg_amnt = $extraTotal;
            $orderCheckingOut->total_amnt += $extraTotal;
            $orderCheckingOut->save();

            DB::commit();

            return response()->json([
                'checked_out' => true,
                'message' => 'Check out done',
                'extrCharge' => $extraTotal,
                'order' => $orderCheckingOut->load('orderItems')
            ]);
        } catch(\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

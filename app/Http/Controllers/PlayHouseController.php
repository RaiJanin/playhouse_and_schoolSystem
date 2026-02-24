<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\PhoneNumber;
use App\Models\M06;
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

    // public function store(StorePlayhouseFormRequest $request)
    // {
    //     $data = $request->validated();

    //     //Temporary
    //     $order_number = '00'.str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

    //     return response()->json([
    //         'isFormSubmitted' => true,
    //         'orderNum' => $order_number,
    //         'data' => $data,
    //     ]);
    // }

    public function store(Request $request)
    {
        $data = $request->all();

        //Temporary
        $order_number = '00'.str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        return response()->json([
            'isFormSubmitted' => true,
            'orderNum' => $order_number,
            'data' => $data,
        ]);
    }

    public function makeOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $OTP = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        $phoneRecord = PhoneNumber::create([
            'phone_number' => $request->phone,
            'otp_code' => $OTP,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        return response()->json([
            'generated' => true,
            'id' => $phoneRecord->id,
            'code' => $OTP
        ]);
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
        
        //Temporary
        //$isOldUser = M06::where('mobileno', $phoneNum)->first();
        $isOldUser = $phoneNum === '09785671234' ? true : false;

        //Set old user as true to simulate "old user" feature

        return response()->json([
            'isCorrectOtp' => true,
            'isOldUser' => $isOldUser,
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
        //Search by number query later

        //Simulate old user data
        $parentData = [
            'parent_name' => 'Romeo',
            'parent_lastname' => 'Agustus',
            'parent_email' => 'agustusmeo@gmail.com',
            'parent_birthday' => '2003-01-17'
        ];

        return response()->json([
            'userLoaded' => true,
            'data' => $parentData
        ]);

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\Playhouse;
use Illuminate\Http\Request;

class PlayHouseController extends Controller
{
    public function store(StorePlayhouseFormRequest $request)
    {
        $data = $request->validated();

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
        
        //Insert numbers on the database here

        return response()->json([
            'generated' => true,
            'code' => $OTP
        ]);
    }

    public function verifyOTP(Request $request, $phoneNum)
    {
        //query for phone number later
        $request->validate(['otp' => 'required|string|size:3']);

        //Simulate returnee
        $isOldUser = true;

        //Query to find the phone number and its OTP

        //Update phone number, label as "verified"

        return response()->json([
            'isCorrectOtp' => true,
            'isOldUser' => $isOldUser,
            'phoneNum' => $phoneNum,
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

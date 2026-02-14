<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use Illuminate\Http\Request;

class PlayHouseController extends Controller
{
    public function store(StorePlayhouseFormRequest $request)
    {
        $data = $request->validated();
        return response()->json([
            'isFormSubmitted' => true,
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

    public function verifyOTP(Request $request)
    {
        
    }
}

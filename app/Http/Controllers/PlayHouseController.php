<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\PhoneNumber;
use App\Models\M06;
use App\Models\M06Child;
use App\Models\ParentInfo;
use App\Models\Guardian;
use App\Models\Child;
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

    public function store(Request $request)
    {
        $data = $request->all();

        try {
            DB::beginTransaction();

            $parent = M06::updateOrCreate(['mobileno' => $data['phone']],[
                'd_name' => $data['parentName'] . ' ' . $data['parentLastName'],
                'firstname' => $data['parentName'],
                'lastname' => $data['parentLastName'],
                'birthday' => $data['parentBirthday'],
                'mobileno' => $data['phone'],
                'email' => $data['parentEmail'],
                'isparent' => true,
                'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
            ]);

            if($request->filled('guardianName'))
            {
                M06::updateOrCreate(['mobileno' => $data['guardianPhone']],[
                    'd_name' => $data['guardianName'] . ' ' . $data['guardianLastName'],
                    'firstname' => $data['guardianName'],
                    'lastname' => $data['guardianLastName'],
                    'mobileno' => $data['guardianPhone'],
                    'isparent' => false,
                    'isguardian' => true,
                    'guardianauthorized' => $data['guardianAuthorized'],
                    'createdby' => $data['parentName'] . ' ' . $data['parentLastName'],
                    'updatedby' => $data['parentName'] . ' ' . $data['parentLastName']
                ]);
            }

            if($request->has('child'))
            {
                foreach($data['child'] as $child) {
                    M06Child::updateOrCreate(
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
                }
            }

            DB::commit();

            return response()->json([
                'isFormSubmitted' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'isFormSubmitted' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
        
    }

    public function checkPhone($phoneNum)
    {
        // Check if phone number exists in guardians (primary contact)
        $guardian = \App\Models\Guardian::whereHas('phoneNumbers', function($query) use ($phoneNum) {
            $query->where('phone_number', $phoneNum);
        })->first();
        
        if ($guardian) {
            return response()->json([
                'isExisting' => true,
                'type' => 'guardian',
                'name' => $guardian->first_name,
                'last_name' => $guardian->last_name,
            ]);
        }
        
        // Check in parent_info table
        $parent = \App\Models\ParentInfo::whereHas('phoneNumbers', function($query) use ($phoneNum) {
            $query->where('phone_number', $phoneNum);
        })->first();
        
        if ($parent) {
            return response()->json([
                'isExisting' => true,
                'type' => 'parent',
                'name' => $parent->first_name,
                'last_name' => $parent->last_name,
            ]);
        }
        
        return response()->json([
            'isExisting' => false,
            'name' => null,
        ]);
    }

    public function makeOtp(Request $request)
    {
        try {
            $request->validate(['phone' => 'required|string|max:20']);

            $OTP = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $phone = $request->phone;

            // Check if phone number already exists
            // $existingPhone = PhoneNumber::where('phone_number', $phone)->first();
            
            // if ($existingPhone) {
            //     // Update existing record with new OTP
            //     $existingPhone->update([
            //         'otp_code' => $OTP,
            //         'otp_expires_at' => Carbon::now()->addMinutes(5),
            //         'is_verified' => false
            //     ]);
                
            //     return response()->json([
            //         'generated' => true,
            //         'id' => $existingPhone->id,
            //         'code' => $OTP
            //     ]);
            // }

            //Allow multiple otp storing for same phone numbers, 
            // so mag duplicate tanan numbers, 
            // kay nagbutang kog delete otp if mulapas ug multiple attempts

            // Create new OTP record
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
        $oldUserData = M06::with(['children', 'guardians'])->where('mobileno', $phoneNumber)->first();

        return response()->json([
            'oldUserData' => $oldUserData,
            'userLoaded' => true,
        ]);
    }
}

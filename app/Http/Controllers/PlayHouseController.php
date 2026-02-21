<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use App\Models\PhoneNumber;
use App\Models\M06;
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

            // Create or find parent (flat field names: parentName, parentLastName, parentEmail, parentBirthday)
            $parent = null;
            if (!empty($data['parentName'])) {
                $parent = ParentInfo::create([
                    'first_name' => $data['parentName'] ?? '',
                    'last_name' => $data['parentLastName'] ?? '',
                    'email' => $data['parentEmail'] ?? null,
                    'birthday' => $data['parentBirthday'] ?? null,
                ]);

                // Save parent phone if provided (from 'phone' field or 'parentPhone' field)
                $phoneNumber = $data['phone'] ?? $data['parentPhone'] ?? null;
                if (!empty($phoneNumber)) {
                    // Check if phone number already exists, if not create new
                    $phone = PhoneNumber::firstOrCreate(
                        ['phone_number' => $phoneNumber],
                        ['phone_number' => $phoneNumber]
                    );
                    $parent->phoneNumbers()->syncWithoutDetaching([$phone->id => ['is_primary' => true]]);
                }
            }

            // Create or find guardian (flat field names: guardianName, guardianLastName, guardianPhone)
            $guardian = null;
            if (!empty($data['guardianName'])) {
                $guardian = Guardian::create([
                    'first_name' => $data['guardianName'] ?? '',
                    'last_name' => $data['guardianLastName'] ?? '',
                    'email' => $data['guardianEmail'] ?? null,
                    'birthday' => $data['guardianBirthday'] ?? null,
                    'relationship' => $data['guardianRelationship'] ?? 'other',
                ]);

                // Save guardian phone if provided
                if (!empty($data['guardianPhone'])) {
                    // Check if phone number already exists, if not create new
                    $phone = PhoneNumber::firstOrCreate(
                        ['phone_number' => $data['guardianPhone']],
                        ['phone_number' => $data['guardianPhone']]
                    );
                    $guardian->phoneNumbers()->syncWithoutDetaching([$phone->id => ['is_primary' => true]]);
                }

                // Link parent and guardian if both exist
                if ($parent && $guardian) {
                    $parent->guardians()->attach($guardian->id);
                }
            }

            // Create children (loop - can have multiple children)
            // Field names: child[0][name], child[0][birthday], child[0][playDuration], child[0][price]
            $savedChildren = [];
            
            // Check for child array
            if (!empty($data['child']) && is_array($data['child'])) {
                foreach ($data['child'] as $childData) {
                    // Map playDuration to playtime_duration
                    $playDuration = $childData['playDuration'] ?? '';
                    
                    // Calculate price based on duration
                    $price = 0;
                    if ($playDuration == '1') {
                        $price = 100;
                    } elseif ($playDuration == '2') {
                        $price = 180;
                    } elseif ($playDuration == '3') {
                        $price = 250;
                    }

                    $child = Child::create([
                        'parent_info_id' => $parent?->id,
                        'guardian_id' => $guardian?->id,
                        'name' => $childData['name'] ?? '',
                        'birthday' => $childData['birthday'] ?? null,
                        'playtime_duration' => $playDuration,
                        'price' => $price,
                    ]);
                    $savedChildren[] = $child;
                }
            }

            DB::commit();

            //Temporary
            $order_number = '00'.str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

            return response()->json([
                'isFormSubmitted' => true,
                'orderNum' => $order_number,
                'data' => $data,
                'saved' => [
                    'parent' => $parent,
                    'guardian' => $guardian,
                    'children' => $savedChildren,
                ]
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

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

            // Create or find guardian (primary contact with phone)
            $guardian = null;
            $phoneNumber = $data['phone'] ?? $data['parentPhone'] ?? null;
            
            // First, try to find existing guardian by phone number
            if (!empty($phoneNumber)) {
                $phone = PhoneNumber::where('phone_number', $phoneNumber)->first();
                if ($phone) {
                    $guardian = Guardian::whereHas('phoneNumbers', function($query) use ($phoneNumber) {
                        $query->where('phone_number', $phoneNumber);
                    })->first();
                }
            }
            
            // Also try to find by email if provided
            $parentEmail = $data['parentEmail'] ?? null;
            if (!$guardian && !empty($parentEmail)) {
                $guardian = Guardian::where('email', $parentEmail)->first();
            }
            
            // If guardian exists and we have guardian data, update; if guardian doesn't exist, create new
            if (!empty($data['parentName'])) {
                if ($guardian) {
                    $guardian->update([
                        'first_name' => $data['parentName'] ?? '',
                        'last_name' => $data['parentLastName'] ?? '',
                        'email' => $data['parentEmail'] ?? null,
                        'birthday' => $data['parentBirthday'] ?? null,
                        'relationship' => $data['guardianRelationship'] ?? 'parent',
                    ]);
                } else {
                    $guardian = Guardian::create([
                        'first_name' => $data['parentName'] ?? '',
                        'last_name' => $data['parentLastName'] ?? '',
                        'email' => $data['parentEmail'] ?? null,
                        'birthday' => $data['parentBirthday'] ?? null,
                        'relationship' => $data['guardianRelationship'] ?? 'parent',
                    ]);
                }

                // Save guardian phone if provided (primary contact has the phone)
                if (!empty($phoneNumber)) {
                    // Find existing phone or create new
                    $phone = PhoneNumber::where('phone_number', $phoneNumber)->first();
                    if (!$phone) {
                        $phone = PhoneNumber::create(['phone_number' => $phoneNumber]);
                    }
                    $guardian->phoneNumbers()->syncWithoutDetaching([$phone->id => ['is_primary' => true]]);
                }
            } elseif ($guardian) {
                // Guardian fields are empty but we found guardian by phone - keep existing guardian
            }

            // Create or find parent (secondary contact, no phone)
            $parent = null;
            // Only create parent if guardianName AND (email OR it's explicitly provided)
            // This prevents creating parent records with empty email from auto-filled data
            if (!empty($data['guardianName']) && !empty($data['guardianEmail'])) {
                $parent = ParentInfo::create([
                    'first_name' => $data['guardianName'] ?? '',
                    'last_name' => $data['guardianLastName'] ?? '',
                    'email' => $data['guardianEmail'] ?? null,
                    'birthday' => $data['guardianBirthday'] ?? null,
                ]);

                // Link parent and guardian if both exist
                if ($parent && $guardian) {
                    $guardian->parentInfos()->attach($parent->id);
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
                        'add_socks' => $childData['addSocks'] ?? false,
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
            $existingPhone = PhoneNumber::where('phone_number', $phone)->first();
            
            if ($existingPhone) {
                // Update existing record with new OTP
                $existingPhone->update([
                    'otp_code' => $OTP,
                    'otp_expires_at' => Carbon::now()->addMinutes(5),
                    'is_verified' => false
                ]);
                
                return response()->json([
                    'generated' => true,
                    'id' => $existingPhone->id,
                    'code' => $OTP
                ]);
            }

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
        
        // Check if user is a returnee by searching in guardians and parent_info tables
        $returneeData = null;
        $phoneNumber = $phoneNum;
        
        // Search in guardians table first (primary contact with phone)
        $guardianWithPhone = \App\Models\Guardian::whereHas('phoneNumbers', function($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        })->with(['children', 'phoneNumbers', 'parentInfos'])->first();
        
        if ($guardianWithPhone) {
            $returneeData = [
                'isReturnee' => true,
                'type' => 'guardian',
                'guardian' => [
                    'id' => $guardianWithPhone->id,
                    'first_name' => $guardianWithPhone->first_name,
                    'last_name' => $guardianWithPhone->last_name,
                    'email' => $guardianWithPhone->email,
                    'relationship' => $guardianWithPhone->relationship,
                ],
                'children' => $guardianWithPhone->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'birthday' => $child->birthday,
                        'playtime_duration' => $child->playtime_duration,
                        'price' => $child->price,
                        'add_socks' => $child->add_socks ?? false,
                    ];
                })->toArray(),
                'parent' => $guardianWithPhone->parentInfos->map(function($parent) {
                    return [
                        'id' => $parent->id,
                        'first_name' => $parent->first_name,
                        'last_name' => $parent->last_name,
                        'email' => $parent->email,
                        'birthday' => $parent->birthday,
                    ];
                })->first() ?? null,
            ];
        } else {
            // Search in parent_info table (secondary)
            $parentWithPhone = \App\Models\ParentInfo::whereHas('phoneNumbers', function($query) use ($phoneNumber) {
                $query->where('phone_number', $phoneNumber);
            })->with(['children', 'phoneNumbers', 'guardians'])->first();
            
            if ($parentWithPhone) {
                $returneeData = [
                    'isReturnee' => true,
                    'type' => 'parent',
                    'parent' => [
                        'id' => $parentWithPhone->id,
                        'first_name' => $parentWithPhone->first_name,
                        'last_name' => $parentWithPhone->last_name,
                        'email' => $parentWithPhone->email,
                        'birthday' => $parentWithPhone->birthday,
                    ],
                    'children' => $parentWithPhone->children->map(function($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'birthday' => $child->birthday,
                            'playtime_duration' => $child->playtime_duration,
                            'price' => $child->price,
                            'add_socks' => $child->add_socks ?? false,
                        ];
                    })->toArray(),
                    'guardians' => $parentWithPhone->guardians->map(function($guardian) {
                        return [
                            'id' => $guardian->id,
                            'first_name' => $guardian->first_name,
                            'last_name' => $guardian->last_name,
                            'relationship' => $guardian->relationship,
                        ];
                    })->toArray(),
                ];
            }
        }
        
        $isOldUser = $returneeData !== null;

        return response()->json([
            'isCorrectOtp' => true,
            'isOldUser' => $isOldUser,
            'phoneNum' => $phoneNum,
            'returneeData' => $returneeData,
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
        // Search in parent_info table via phone_numbers relationship
        $parent = \App\Models\ParentInfo::whereHas('phoneNumbers', function($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        })->with(['children', 'phoneNumbers', 'guardians'])->first();
        
        if ($parent) {
            return response()->json([
                'userLoaded' => true,
                'found' => true,
                'type' => 'parent',
                'data' => [
                    'parent_id' => $parent->id,
                    'parent_name' => $parent->first_name,
                    'parent_lastname' => $parent->last_name,
                    'parent_email' => $parent->email,
                    'parent_birthday' => $parent->birthday,
                ],
                'children' => $parent->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'birthday' => $child->birthday,
                        'playtime_duration' => $child->playtime_duration,
                        'price' => $child->price,
                    ];
                })->toArray(),
                'guardians' => $parent->guardians->map(function($guardian) {
                    return [
                        'id' => $guardian->id,
                        'first_name' => $guardian->first_name,
                        'last_name' => $guardian->last_name,
                        'relationship' => $guardian->relationship,
                    ];
                })->toArray(),
            ]);
        }
        
        // Search in guardians table
        $guardian = \App\Models\Guardian::whereHas('phoneNumbers', function($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        })->with(['children', 'phoneNumbers'])->first();
        
        if ($guardian) {
            return response()->json([
                'userLoaded' => true,
                'found' => true,
                'type' => 'guardian',
                'data' => [
                    'guardian_id' => $guardian->id,
                    'guardian_name' => $guardian->first_name,
                    'guardian_lastname' => $guardian->last_name,
                    'guardian_relationship' => $guardian->relationship,
                ],
                'children' => $guardian->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'birthday' => $child->birthday,
                        'playtime_duration' => $child->playtime_duration,
                        'price' => $child->price,
                    ];
                })->toArray(),
            ]);
        }
        
        // Not found
        return response()->json([
            'userLoaded' => false,
            'found' => false,
            'message' => 'Phone number not found in our database'
        ]);
    }
}

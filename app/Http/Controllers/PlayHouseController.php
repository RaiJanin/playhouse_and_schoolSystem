<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlayHouseController extends Controller
{
    public function landing()
    {
        return view('pages.playhouse-landing');
    }

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

        //Query to find the phone number and its OTP

        //Update phone number, label as "verified"

        return response()->json([
            'isCorrectOtp' => true
        ]);
    }

    public function searchReturnee(Request $request)
    {
        // Validate phone number input
        $request->validate([
            'mobileno' => [
                'required',
                'string',
                'regex:/^(\+63|0)?9\d{9}$/'
            ]
        ]);

        // Normalize phone number
        $phone = preg_replace('/^(\+63|0)/', '0', $request->mobileno);

        // Search in M06 table (parents/guardians)
        $parent = \App\Models\M06::where('mobileno', $phone)
            ->where('isparent', true)
            ->first();

        // If not found as parent, try as guardian
        if (!$parent) {
            $parent = \App\Models\M06::where('mobileno', $phone)
                ->where('isguardian', true)
                ->first();
        }

        // If phone number not found at all
        if (!$parent) {
            return redirect()->back()
                ->with('error', 'No account found with this phone number. Please register as a new customer.');
        }

        // Get children linked to this parent via d_code
        $children = \App\Models\M06Child::where('d_code', $parent->d_code)->get();

        // Store customer data in session using M06 structure
        session([
            'registration_type' => 'returnee',
            'd_code' => $parent->d_code,
            'parent_name' => $parent->firstname . ' ' . $parent->lastname,
            'parent_email' => $parent->email ?? null,
            'parent_birthday' => $parent->birthday,
            'mobileno' => $parent->mobileno,
            'isguardian' => $parent->isguardian,
            'children' => $children->map(function($child) {
                return [
                    'd_code_c' => $child->d_code_c,
                    'firstname' => $child->firstname,
                    'lastname' => $child->lastname,
                    'birthday' => $child->birthday,
                    'age' => $child->age,
                ];
            })->toArray(),
        ]);

        // Redirect to registration page (skip phone/OTP steps)
        return redirect()->route('playhouse.registration', ['type' => 'returnee'])
            ->with('success', 'Welcome back, ' . $parent->firstname . '!');
    }
        public function registration(Request $request)
    {
        $type = $request->get('type', session('registration_type', 'new'));
        $parentData = null;
        $startStep = 'phone';

        // If returnee, skip phone and OTP steps
        if ($type === 'returnee' || session('registration_type') === 'returnee') {
            $dCode = session('d_code');
            
            if ($dCode) {
                // Get parent data from M06 table
                $parentData = \App\Models\M06::where('d_code', $dCode)->first();

                if ($parentData) {
                    // Get children from M06Child table
                    $parentData->children = \App\Models\M06Child::where('d_code', $dCode)->get();

                    $startStep = 'parent'; // Skip to step 3 (Parent Info)
                }
            }
        }

        return view('pages.playhouse-registration', [
            'startStep' => $startStep,
            'parentData' => $parentData,
            'registrationType' => $type,
        ]);
    }
    public function clearSession()
    {
        session()->forget([
            'registration_type',
            'd_code',              // M06 primary key
            'parent_name',
            'parent_email',
            'parent_birthday',
            'mobileno',            // M06 phone field
            'isguardian',
            'children',
        ]);

        return redirect()->route('playhouse.landing');
    }
}
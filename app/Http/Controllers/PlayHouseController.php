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
            'phone' => 'required|string|min:11'
        ]);

        // Search phone number in phone_numbers table (RAW QUERY)
        $phoneNumber = DB::table('phone_numbers')
            ->where('phone_number', $request->phone)
            ->first();

        // If phone number not found
        if (!$phoneNumber) {
            return redirect()->back()
                ->with('error', 'No account found with this phone number. Please register as a new customer.');
        }

        // Get customer record using pivot table (many-to-many) (RAW QUERY)
        $customer = DB::table('customer_records')
            ->join('customer_phone', 'customer_records.id', '=', 'customer_phone.customer_record_id')
            ->where('customer_phone.phone_number_id', $phoneNumber->id)
            ->select('customer_records.*', 'customer_phone.is_primary')
            ->orderBy('customer_phone.is_primary', 'desc')
            ->first();

        // If customer not found
        if (!$customer) {
            return redirect()->back()
                ->with('error', 'Customer record not found.');
        }

        // Store customer data in session
        Session::put([
            'registration_type' => 'returnee',
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'customer_email' => $customer->email,
            'customer_birthday' => $customer->birthday,
            'phone_number' => $phoneNumber->phone_number,
        ]);

        // Redirect to registration page (will skip phone/OTP steps)
        return redirect()->route('playhouse.registration')
            ->with('success', 'Welcome back! We found your account.');
    }

    public function registration(Request $request)
    {
        $type = Session::get('registration_type', 'new');
        $customerData = null;
        $startStep = 'phone';

        // If returnee, skip phone and OTP steps
        if ($type === 'returnee') {
            $customerId = Session::get('customer_id');
            
            if ($customerId) {
                // Get customer data (RAW QUERY)
                $customerData = DB::table('customer_records')
                    ->where('id', $customerId)
                    ->first();

                if ($customerData) {
                    // Get phone numbers (RAW QUERY)
                    $customerData->phoneNumbers = DB::table('phone_numbers')
                        ->join('customer_phone', 'phone_numbers.id', '=', 'customer_phone.phone_number_id')
                        ->where('customer_phone.customer_record_id', $customerId)
                        ->select('phone_numbers.*', 'customer_phone.is_primary')
                        ->get();

                    // Get children (RAW QUERY)
                    $customerData->children = DB::table('children')
                        ->where('customer_record_id', $customerId)
                        ->get();

                    $startStep = 'parent'; // Skip to step 3 (Parent)
                }
            }
        }

        return view('pages.playhouse-registration', [
            'startStep' => $startStep,
            'customerData' => $customerData,
        ]);
    }

    public function clearSession()
    {
        Session::forget([
            'registration_type',
            'customer_id',
            'customer_name',
            'customer_email',
            'customer_birthday',
            'phone_number',
        ]);

        return redirect()->route('playhouse.landing');
    }
}
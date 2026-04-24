<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M06;

class InformationsController extends Controller
{
    public function getContact(Request $request)
    {
        try
        {
            $request->validate([
                'contact_num' => 'nullable|string|max:20'
            ]);

            $query = M06::query();
            $query->select(['mobileno', 'd_name', 'email', 'isparent']);

            $contactDetails = $query->where('mobileno', $request->contact_num)->first();

            if(!$contactDetails)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Data unavailable',
                    'error' => 'Data may not exist or broken'
                ]);
            }

            if($contactDetails->isparent)
            {
                $contactDetails->type = 'parent';
            } 
            else
            {
                $contactDetails->type = 'guardian';
            }

            $data = [
                'name' => $contactDetails->d_name,
                'email' => $contactDetails->email,
                'contact_number' => $contactDetails->mobileno,
                'pgtype' => $contactDetails->type
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}

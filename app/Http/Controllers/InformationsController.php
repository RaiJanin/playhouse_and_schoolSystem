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
                'search' => 'nullable|string|max:20',

            ]);

            $query = M06::query();
            $query->select(['d_code', 'mobileno', 'd_name', 'email', 'isparent']);

            $data = [];

            $contactDetails = $query->where(
                function ($search) use ($request) {
                    $search->where('mobileno', 'like', '%' . $request->search . '%')
                        ->orWhere('d_name', 'like', '%' . $request->search . '%');
                }
            )->get()->toArray();

            if(empty($contactDetails))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Data unavailable',
                    'contact' => [],
                    'error' => 'Data may not exist or broken'
                ]);
            }

            $data = array_map(function ($contact)
                {
                    return [
                        'id' => $contact['d_code'],
                        'name' => $contact['d_name'],
                        'email' => $contact['email'],
                        'contact_number' => $contact['mobileno'],
                    ];
                }, $contactDetails);
            
            return response()->json([
                'success' => true,
                'contact' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayhouseFormRequest;
use Illuminate\Http\Request;

class PlayHouseController extends Controller
{
    public function store(StorePlayhouseFormRequest $request)
    {
        $data = $request->validated();
        return response()->json($data);
    }
}

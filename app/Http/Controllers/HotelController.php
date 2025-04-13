<?php

namespace App\Http\Controllers;

use App\Models\Hotel;


class HotelController extends Controller
{
    public function read(){

        $hotels= Hotel::all();

        return response()->json([
            'hotels'=> $hotels,
            'message'=> 'Hotels retrieved successfully'
        ], 200);
        
    }
}

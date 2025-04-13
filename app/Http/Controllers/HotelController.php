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

    public function getRooms($hotelId){

        $hotel= Hotel::find($hotelId);

        $rooms= $hotel->rooms;
        
        return response()->json([
            "message"=> "Rooms retrieved successfully",
            "available_rooms"=> $rooms,
        ], 200);
    }
}

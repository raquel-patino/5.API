<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Exception;

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

    try{
        $hotel= Hotel::findOrFail($hotelId);

        $rooms= $hotel->rooms;
        
        return response()->json([
            "message"=> "Rooms retrieved successfully",
            "available_rooms"=> $rooms,
        ], 200);
    }catch(Exception $e){
        return response()->json([
            'message'=> 'Id is not valid',
            'error'=> $e->getMessage()
        ], 404);
        
    }

    }
}

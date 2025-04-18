<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Http\Requests\GetHotelsRequest;

class HotelController extends Controller
{
    public function read(GetHotelsRequest $request){

        $validatedData= $request->validated();
        $places= Hotel::pluck('place');
        
        $hotels=Hotel::where('place', '=', $validatedData['place'])->get();
        if($hotels->isEmpty()){
            return response()->json([
                'error'=> 'We don´t have available hotels on that location',
                'message'=>'Check-out our available locations:'. $places->join(', ')
            ], 404);
        }
        $checkIn= Carbon::parse($validatedData['check_in']);
        $checkOut=Carbon::parse($validatedData['check_out']);
        $roomsId=[];

        foreach($hotels as $hotel){
          $reservations= $hotel->reservations;
            foreach($reservations as $reservation){
                if ($checkIn < $reservation->check_out && $checkOut > $reservation->check_in){
                    $roomsId[$reservation->hotel_id][]= $reservation->room_id;
                }

            }
        }
        
        
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

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
    public function getHotels(GetHotelsRequest $request){

        $validatedData= $request->validated();
        
        $hotels= $this->getHotelsByCountry($validatedData['country'] ?? null);
        $responseHotelsEmpty= $this->abortIfHotelsIsEmpty($hotels);
        if ($responseHotelsEmpty) {
            return $responseHotelsEmpty;
        }

        $checkIn= Carbon::parse($validatedData['check_in']);
        $checkOut=Carbon::parse($validatedData['check_out']);
      
        $occupiedRooms= $this->getCollectionOccupiedRooms($hotels, $checkIn, $checkOut);
        $availableRooms= $this->searchAvailableRoomsByHotel($hotels, $occupiedRooms);

        $availableHotels = Hotel::findMany(array_keys($availableRooms->toArray()));
        
        return response()->json([
            'Available hotels'=> $availableHotels,
            'message'=> 'Hotels retrieved successfully'
        ], 200);
        
    }

public function getRooms(GetHotelsRequest $request, $hotelId){

    $validatedData= $request->validated();
    $checkIn= Carbon::parse($validatedData['check_in']);
    $checkOut=Carbon::parse($validatedData['check_out']);

        $hotel= Hotel::findOrFail($hotelId);
        $roomsId= $this->getOccupiedRoomsIds($hotel,$checkIn, $checkOut);
        $availableRooms= $this->getAvailableRooms($hotelId, $roomsId);
  
    return response()->json([
            "message"=> "Rooms retrieved successfully",
            "available_rooms"=> $availableRooms,
        ], 200);
        
    }

    private function getOccupiedRoomsIds($hotel, $checkIn, $checkOut){
        $roomsId=[];
        $reservations= $hotel->reservations;
        foreach($reservations as $reservation){
            if ($checkIn < $reservation->check_out && $checkOut > $reservation->check_in){
                $roomsId[]= $reservation->room_id;
            }
        }
        return $roomsId;
    }

    private function getCollectionOccupiedRooms($hotels, $checkIn, $checkOut ){
        $roomsId=[];
        foreach($hotels as $hotel){
            $roomsId= $this->getOccupiedRoomsIds($hotel,$checkIn, $checkOut);
            
        }
        return $roomsId;
    }

    private function searchAvailableRoomsByHotel($hotels, $roomsId){

        $availableRooms = Room::whereIn('hotel_id', $hotels->pluck('id'))
        ->whereNotIn('id', $roomsId)
        ->get()
        ->groupBy('hotel_id');
    
    return $availableRooms;
    }

    private function getHotelsByCountry($country){

        if ($country === null){
            $hotels=Hotel::all();
         }else{
         $hotels=Hotel::where('country', '=', $country)->get();
         }

        return $hotels;
    }

    private function abortIfHotelsIsEmpty($hotels){

        $places= Hotel::pluck('country');
        if($hotels->isEmpty()){
            return response()->json([
                'error'=> 'We don´t have available hotels on that location',
                'message'=>'Check-out our available locations:'. $places->join(', ')
            ], 404);
        }
        return null;
    }

    private function getAvailableRooms($hotelId, $roomsId){

        $availableRooms = Room::where('hotel_id','=', $hotelId)
        ->whereNotIn('id', $roomsId)
        ->get();

        return $availableRooms;
    }

    
        
    
}

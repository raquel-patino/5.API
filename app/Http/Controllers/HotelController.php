<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Http\Requests\GetHotelsRequest;
use App\Services\HotelService;
use App\Services\RoomAvailabilityService;


class HotelController extends Controller
{
    public function index(GetHotelsRequest $request) {
        $validated = $request->validated();
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
    
        $hotels = HotelService::getHotelsByCountry($validated['country'] ?? null);
        if ($response = HotelService::abortIfHotelsIsEmpty($hotels)) return $response;
    
        $occupiedRooms = RoomAvailabilityService::getCollectionOccupiedRooms($hotels, $checkIn, $checkOut);
        $availableRooms = RoomAvailabilityService::searchAvailableRoomsByHotel($hotels, $occupiedRooms);
        $availableHotels = Hotel::findMany(array_keys($availableRooms->toArray()));
    
        return response()->json([
            'Available hotels' => $availableHotels,
            'message' => 'Hotels retrieved successfully'
        ], 200);
    }

    public function getRooms(GetHotelsRequest $request, $hotelId) {
        $validated = $request->validated();
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
    
        $hotel = Hotel::findOrFail($hotelId);
        $occupiedRoomIds = RoomAvailabilityService::getOccupiedRoomsIds($hotel, $checkIn, $checkOut);
        $availableRooms = RoomAvailabilityService::getAvailableRooms($hotelId, $occupiedRoomIds);
    
        return response()->json([
            "message" => "Rooms retrieved successfully",
            "available_rooms" => $availableRooms,
        ], 200);
    }

    
        
    
}

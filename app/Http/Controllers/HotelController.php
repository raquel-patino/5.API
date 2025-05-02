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

    /**
 * @OA\Get(
 *     path="/hotels",
 *     summary="Get available hotels by country and dates",
 *     tags={"Hotels"},
 *     security={{"Bearer":{}}}, 
 *     @OA\Parameter(
 *         name="country",
 *         in="query",
 *         description="Country to filter hotels (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="Finlandia")
 *     ),
 *     @OA\Parameter(
 *         name="check_in",
 *         in="query",
 *         description="Check-in date",
 *         required=true,
 *         @OA\Schema(type="string", format="date", example="2025-06-01")
 *     ),
 *     @OA\Parameter(
 *         name="check_out",
 *         in="query",
 *         description="Check-out date",
 *         required=true,
 *         @OA\Schema(type="string", format="date", example="2025-06-10")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Available hotels retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="Available hotels", type="array", @OA\Items(type="object")),
 *             @OA\Property(property="message", type="string", example="Hotels retrieved successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No hotels found in the selected location"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

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
/**
 * @OA\Get(
 *     path="/hotels/{id}/rooms",
 *     summary="Get available rooms for a hotel by dates",
 *     tags={"Hotels"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the hotel",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="check_in",
 *         in="query",
 *         description="Check-in date",
 *         required=true,
 *         @OA\Schema(type="string", format="date", example="2025-06-01")
 *     ),
 *     @OA\Parameter(
 *         name="check_out",
 *         in="query",
 *         description="Check-out date",
 *         required=true,
 *         @OA\Schema(type="string", format="date", example="2025-06-10")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Available rooms retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Rooms retrieved successfully"),
 *             @OA\Property(property="available_rooms", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Hotel not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

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

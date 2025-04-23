<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;

class RoomAvailabilityService
{

    public static function getOccupiedRoomsIds($hotel, Carbon $checkIn, Carbon $checkOut)
    {
        $occupied = [];
        foreach ($hotel->reservations as $reservation) {
            if ($checkIn < $reservation->check_out && $checkOut > $reservation->check_in) {
                $occupied[] = $reservation->room_id;
            }
        }
        return $occupied;
    }
    
    public static function getCollectionOccupiedRooms($hotels, Carbon $checkIn, Carbon $checkOut)
    {
        $roomsId = [];
        foreach ($hotels as $hotel) {
            $roomsId = array_merge(
                $roomsId,
                self::getOccupiedRoomsIds($hotel, $checkIn, $checkOut)
            );
        }
        return $roomsId;
    }
    
    public static function searchAvailableRoomsByHotel($hotels, $occupiedRooms)
    {
        return Room::whereIn('hotel_id', $hotels->pluck('id'))
            ->whereNotIn('id', $occupiedRooms)
            ->get()
            ->groupBy('hotel_id');
    }
    
    public static function getAvailableRooms($hotelId, $occupiedRoomIds)
    {
        return Room::where('hotel_id', $hotelId)
            ->whereNotIn('id', $occupiedRoomIds)
            ->get();
    }
    
}
?>
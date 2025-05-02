<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;

class ReservationService
{
    public static function isRoomAvailable($roomId, $checkIn, $checkOut, $excludedReservationId = null)
    {
        return !Reservation::where('room_id', $roomId)
            ->when($excludedReservationId, fn($query) =>
                $query->where('id', '!=', $excludedReservationId)
            )
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })
            ->exists();
    }

    public static function calculateReservationPrice($roomId, $checkIn, $checkOut)
    {
        $room = Room::find($roomId);
        if (!$room || !$room->price) {
            throw new \Exception("Room not found or price not set");
        }

        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate);

        return $nights * $room->price;
    }
}

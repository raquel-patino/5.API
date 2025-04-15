<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateReservationRequest;

class ReservationController extends Controller
{
    public function create(CreateReservationRequest $request){
        $validatedData= $request->validated();
        $reservation= Reservation::make($validatedData);
        $reservation->price= $this->calculatePrice($validatedData['room_id'], $validatedData['check_in'], $validatedData['check_out']);
        $reservation->user_id= Auth::user()->id;
        $reservation->save();

        return response()->json([
            'message'=> 'Reservation created successfully',
            'reservation'=> $reservation
        ], 201);
    }


    
    private function calculatePrice($roomId, $checkIn, $checkOut)
    {
        $room= Room::find($roomId);
        $pricePerNight= $room->price;
        if (!$pricePerNight) {
            return response()->json(['error' => 'Room not found or price not set'], 404);
        }
        $checkInDate= \Carbon\Carbon::parse($checkIn);
        $checkOutDate= \Carbon\Carbon::parse($checkOut);
        $numberOfNights= $checkInDate->diffInDays($checkOutDate);
        $totalPrice= $pricePerNight * $numberOfNights;

        return $totalPrice;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Support\Facades\Gate;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    public function store(CreateReservationRequest $request)
    {
        $validatedData = $request->validated();

        if (!ReservationService::isRoomAvailable($validatedData['room_id'], $validatedData['check_in'], $validatedData['check_out'])) {
            return response()->json([
                'error' => 'The selected room is not available for those dates.'
            ], 409);
        }

        try {
            $reservation = Reservation::make($validatedData);
            $reservation->price = ReservationService::calculateReservationPrice(
                $validatedData['room_id'],
                $validatedData['check_in'],
                $validatedData['check_out']
            );
            $reservation->user_id = Auth::id();
            $reservation->save();

            return response()->json([
                'message' => 'Reservation created successfully',
                'reservation' => $reservation
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reservations = $user->reservations;
        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No reservations found'], 404);
        }

        return response()->json([
            "message" => "Reservations retrieved successfully",
            "reservations" => $reservations
        ], 200);
    }
    

    public function update(UpdateReservationRequest $request, $reservationId)
    {
        $validatedData = $request->validated();
        $reservation = Reservation::findOrFail($reservationId);
        Gate::authorize('update', $reservation);

        $checkIn = $validatedData['check_in'] ?? $reservation->check_in;
        $checkOut = $validatedData['check_out'] ?? $reservation->check_out;
        $roomId = $validatedData['room_id'] ?? $reservation->room_id;

        if (!ReservationService::isRoomAvailable($roomId, $checkIn, $checkOut, $reservation->id)) {
            return response()->json([
                'error' => 'The selected room is not available for those dates.'
            ], 409);
        }

        foreach ($validatedData as $key => $data) {
            if ($data !== null) {
                $reservation[$key] = $data;
            }
        }

        $reservation->price = ReservationService::calculateReservationPrice($roomId, $checkIn, $checkOut);
        $reservation->save();

        return response()->json([
            "updated reservation" => $reservation,
            "message" => "Update successful"
        ], 200);
    }

    public function destroy($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        Gate::authorize('delete', $reservation);
        $reservation->delete();

        return response()->json([
            "message" => 'Reservation cancelled successfully',
        ], 200);
    }
}
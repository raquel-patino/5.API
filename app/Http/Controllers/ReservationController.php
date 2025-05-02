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
    /**
 * @OA\Post(
 *     path="/reservations",
 *     summary="Create a new reservation",
 *     tags={"Reservations"},
 *     security={{"Bearer":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"hotel_id","number_guests","room_id", "check_in", "check_out"},
 *             @OA\Property(property="hotel_id", type="integer", example=1),
 *             @OA\Property(property="number_guests", type="integer", example=2),
 *             @OA\Property(property="room_id", type="integer", example=3),
 *             @OA\Property(property="check_in", type="string", format="date", example="2025-06-01"),
 *             @OA\Property(property="check_out", type="string", format="date", example="2025-06-10")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Reservation created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reservation created successfully"),
 *             @OA\Property(property="reservation", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Room not available for selected dates"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Error creating reservation"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

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

/**
 * @OA\Get(
 *     path="/reservations",
 *     summary="Get authenticated user's reservations",
 *     tags={"Reservations"},
 *     security={{"Bearer":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Reservations retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="reservations",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="check_in", type="string", format="date", example="2025-05-01"),
 *                     @OA\Property(property="check_out", type="string", format="date", example="2025-05-05"),
 *                     @OA\Property(property="hotel_name", type="string", example="Hotel California"),
 *                     @OA\Property(property="room_type", type="string", example="Suite"),
 *                     @OA\Property(property="price", type="number", format="float", example=199.99),
 *                     @OA\Property(property="hotel_id", type="integer", example=3)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No reservations found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No reservations found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */


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
            'reservations' => $user->reservations->map(function ($r) {
                return [
                    'id' => $r->id,
                    'check_in' => $r->check_in,
                    'check_out' => $r->check_out,
                    'hotel_name' => $r->hotel->name,
                    'room_type' => $r->room->type,
                    'price' => $r->price,
                    'hotel_id' => $r->hotel_id,
                ];
            })
        ],200);
    }
        
/*
Metodo modificado para el frontend
        return response()->json([
            "message" => "Reservations retrieved successfully",
            "reservations" => $reservations
        ], 200);
    }
    */
/**
 * @OA\Put(
 *     path="/reservations/{id}",
 *     summary="Update an existing reservation",
 *     tags={"Reservations"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the reservation to update",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="room_id", type="integer", example=2),
 *             @OA\Property(property="check_in", type="string", format="date", example="2025-06-01"),
 *             @OA\Property(property="check_out", type="string", format="date", example="2025-06-10")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="updated reservation", type="object"),
 *             @OA\Property(property="message", type="string", example="Update successful")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found"
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Room not available for selected dates"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

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

/**
 * @OA\Delete(
 *     path="/reservations/{id}",
 *     summary="Cancel an existing reservation",
 *     tags={"Reservations"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the reservation to cancel",
 *         required=true,
 *         @OA\Schema(type="integer", example=9)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation cancelled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reservation cancelled successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

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
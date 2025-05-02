<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_reservation()
    {
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
        $user= User::factory()->create();
        Passport::actingAs($user);
        $room= Room::first();
        

        $reservation= Reservation::factory()->make();
        $reservation ['user_id']= $user->id;
        $reservation ['room_id']= $room->id;
        $reservation ['hotel_id']= $room->hotel_id;
        $reservation->save();
        $checkIn = fake()->dateTimeBetween('now', '+1 month');

        $response= $this->putJson("api/reservations/{$reservation->id}", ['check_in' => $checkIn->format('Y-m-d')]);

        $response->assertStatus(200);
    }

    public function test_reservation_not_updated_because_of_availability(){
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
        $user= User::factory()->create();
        Passport::actingAs($user);
        $room= Room::first();
        

        $reservation= Reservation::factory()->make();
        $reservation ['user_id']= $user->id;
        $reservation ['room_id']= $room->id;
        $reservation ['hotel_id']= $room->hotel_id;
        $reservation['check_in'] = "2025-05-24";
        $reservation['check_out'] = '2025-05-27';

        $reservation->save();
        

        $reservationTwo= Reservation::factory()->make();
        $reservationTwo ['user_id']= $user->id;
        $reservationTwo ['room_id']= $room->id;
        $reservationTwo ['hotel_id']= $room->hotel_id;
        $reservationTwo['check_out'] = '2025-05-29';
        $reservationTwo->save();
       

        $response= $this->putJson("api/reservations/{$reservationTwo->id}", ['check_in' => "2025-05-24"]);

        $response->assertStatus(409);
    }


}

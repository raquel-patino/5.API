<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoicesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_invoice_is_generated(): void
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
        $response = $this->get("/api/invoices/reservations/{$reservation->id}");

        $response->assertStatus(200);
    }

    public function test_user_is_not_allowed(){
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
        $user= User::factory()->create();
        Passport::actingAs($user);
        $room= Room::first();
        $secondUser= User::factory()->create();

        $reservation= Reservation::factory()->make();
        $reservation ['user_id']= $user->id;
        $reservation ['room_id']= $room->id;
        $reservation ['hotel_id']= $room->hotel_id;
        $reservation->save();

        $reservationTwo= Reservation::factory()->make();
        $reservationTwo ['user_id']= $secondUser->id;
        $reservationTwo ['room_id']= $room->id;
        $reservationTwo ['hotel_id']= $room->hotel_id;
        $reservationTwo->save();

        $response = $this->get("/api/invoices/reservations/{$reservationTwo->id}");

        $response->assertStatus(403);
    }
}

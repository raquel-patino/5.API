<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;

use App\Models\Reservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowReservationsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_reservations_are_shown(): void
    {
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
        
     
       $room=Room::first();
       $user= User::factory()->create();
       Passport::actingAs($user); 
       $reservation= Reservation::factory()->make();
       $reservation ['room_id']= $room->id;
       $reservation['hotel_id'] =$room->hotel_id;
       $reservation ['user_id']= $user->id;
       $reservation->save();
       
       $response= $this->getJson('api/reservations');

        $response->assertStatus(200);
    }
}

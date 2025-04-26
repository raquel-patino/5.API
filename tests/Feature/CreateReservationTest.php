<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateReservationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_can_create_reservation()
    {
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
       
        $user= User::factory()->create();
        Passport::actingAs($user);
        $room= Room::first();

        $response= $this->postJson('api/reservations', [
            'check_in'=> '2025-05-02',
            'check_out'=> '2025-05-04',
            'number_guests'=> 2,
            'hotel_id' =>$room->hotel_id,
            'room_id'=> $room->id,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('reservations', ['user_id'=> $user->id]);
}
    public function test_user_cannot_create_reservation_with_invalid_dates()
    {
        $this->seed(\Database\Seeders\HotelSeeder::class);
        $this->seed(\Database\Seeders\RoomSeeder::class);
       
        $user= User::factory()->create();
        Passport::actingAs($user);
        $room= Room::first();

        $response= $this->postJson('api/reservations', [
            'check_in'=> '2025-05-04',
            'check_out'=> '2025-05-02',
            'number_guests'=> 2,
            'hotel_id' =>$room->hotel_id,
            'room_id'=> $room->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['check_out']);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_reservation()
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

        $response= $this->deleteJson("api/reservations/{$reservation->id}");

        $response->assertStatus(200);
        

    }

    public function test_user_is_not_authorized_to_delete_reservation(){

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


        $response= $this->deleteJson("api/reservations/{$reservationTwo->id}");

        $response->assertStatus(403);



    }

    public function test_reservation_id_doesnt_exists(){
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

        $response= $this->deleteJson('api/reservations/89');

        $response->assertStatus(404);
      

    }
}

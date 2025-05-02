<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Database\Seeders\RoomSeeder;
use Database\Seeders\HotelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetRoomsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_get_rooms_by_hotel_id()
    {
        $this->seed(HotelSeeder::class);
        $this->seed(RoomSeeder::class);
        $user= User::factory()->create();
        Passport::actingAs($user, [], 'api');

        $response = $this->getJson('/api/hotels/1/rooms?check_in=2025-05-23&check_out=2025-05-28');

        $response->assertStatus(200);
       
    }

    public function test_id_doesnt_exists(){
        $this->seed(HotelSeeder::class);
        $this->seed(RoomSeeder::class);
        $user= User::factory()->create();
        Passport::actingAs($user, [], 'api');

        $response = $this->getJson('/api/hotels/3/rooms?check_in=2025-05-23&check_out=2025-05-28');

        $response->assertStatus(404);
       

    }
}

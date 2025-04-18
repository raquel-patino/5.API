<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
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
        
        $response = $this->getJson('api/reservations');
       $user= User::factory()->create();
       Passport::actingAs($user); 
       
       $response= $this->getJson('api/reservations');

        $response->assertStatus(200);
    }
}

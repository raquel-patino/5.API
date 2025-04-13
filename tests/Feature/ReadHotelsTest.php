<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadHotelsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_retrieve_hotels()
    {
        $user= User::factory()->create();
        Passport::actingAs($user, [], 'api');

        $response= $this->getJson('/api/hotels');

        $response->assertStatus(200);
        $response->assertJsonIsArray('hotels');
    }

    public function test_database_has_hotels(){
        
        $this->seed(\Database\Seeders\HotelSeeder::class);
        
        $this->assertDatabaseCount('hotels', 2);

    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProfile extends TestCase
{
    /**
     * A basic feature test example.
     * 
     */
    use RefreshDatabase;

    public function test_user_can_delete_profile()
    {
        $this->seed(\Database\Seeders\PassportSeeder::class);
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson('/api/users');

        $response->assertStatus(200);
        
    }
}

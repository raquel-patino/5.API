<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $this->seed(\Database\Seeders\PassportSeeder::class);
        $user = User::factory()->create(['password'=> bcrypt('1234abcd')]);
        
        $token = $user->createToken('TestToken')->accessToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/logout', ['email'=> $user->email, 'password' => '1234abcd']);
    
        $response->assertStatus(200);
    }
    
   
}


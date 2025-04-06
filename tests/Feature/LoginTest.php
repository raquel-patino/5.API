<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\PassportServiceProvider;


class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_login()
    {
        $this->seed(\Database\Seeders\PassportSeeder::class);
        $user= User::factory()->create(['password'=> bcrypt('1234abcd')]);

        $response = $this->postJson('/api/login', ['password'=> '1234abcd', 'email'=>$user->email]);
        
        $response->assertStatus(200);

    }
}

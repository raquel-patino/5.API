<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_admin_can_get_users(): void
    {
        
        $user= User::factory()->make();
        $user['user_type'] = 'admin';
        $user->save();
        Passport::actingAs($user);

        $response = $this->get('api/admin/users');

        $response->assertStatus(200);
    }


}

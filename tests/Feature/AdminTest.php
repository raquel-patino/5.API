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

        $response = $this->getJson('api/admin/users');

        $response->assertStatus(200);
    }

    public function test_admin_can_change_rol(){

        $admin= User::factory()->make();
        $admin['user_type'] = 'admin';
        $admin->save();
        Passport::actingAs($admin);

        $user= User::factory()->create();

        $response = $this->patchJson("api/admin/users/{$user->id}");

        $response->assertStatus(200);
    }
    
}

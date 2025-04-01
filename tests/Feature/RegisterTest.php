<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    
    public function test_email_has_correct_format(){
        $user= User::factory()->create();
        $userData= $user->toArray();
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
    }
    /**
     * @dataProvider invalidEmails
     */

    public function test_email_fails_with_incorrect_format($email): void
    {   
        $user= User::factory()->make();
        $userData= $user->toArray();
        $userData ['email']= $email;
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_email_is_not_unique_in_database(){

        $user= User::factory()->create();
        $userToTest= User::factory()->make();
        $userData= $userToTest->toArray();
        $userData ['email']= $user->email;
        $response= $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);


    }

    public static function invalidEmails(){

        return [
            'no @'=> ['usuariohotmail.com'],
            'no .com' => ['usuario@hotmail'],
            'no domain'=> ['usuario@'],
            'espaces'=> ['usuario@ hotmail. com'],
            'empty'=> ['']

        ];
    }
}

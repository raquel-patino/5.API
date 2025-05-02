<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModifyUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_see_profile()
    {
        Passport::actingAs(
            User::factory()->create()
        );
       
        $response= $this->getJson('/api/users');

        return $response->assertStatus(200);
    }

    public function test_name_is_too_long(){
        $user= Passport::actingAs(
            User::factory()->create()
        );
        $userArray= $user->toArray();
        $data= array_merge($userArray, ['name'=>'lukeiamyourfatherlukeiamyourfather']);
        
        $response = $this->putJson('/api/users', $data);
       
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }


    #[DataProvider('invalidEmails')]
    public function test_email_is_not_valid($email){
        $user= Passport::actingAs(
            User::factory()->create()
        );
        $userArray= $user->toArray();
        $data= array_merge($userArray, ['email'=>$email]);
        
        $response = $this->putJson('/api/users', $data);
       
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

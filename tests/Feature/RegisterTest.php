<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    //tests de datos nullable pendientes si hay tiempo
    public function test_name_is_too_long(){
        
        $userData= $this->makeUserwithDataToTest(['name'=> 'lukeiamyourfatherlukeiamyourfather']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }


    public function test_name_is_empty(){
        
        $userData= $this->makeUserwithDataToTest(['name'=>'']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_surname_is_empty(){
        $userData= $this->makeUserwithDataToTest(['surname'=>'']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['surname']);
    }

    public function test_surname_is_too_long(){
        
        $userData= $this->makeUserwithDataToTest(['surname'=>'iwilltrytobeaseriouspersonforthisonebutimnotgonnamakeit']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['surname']);
    }

    public function test_username_is_not_unique(){
        $user= User::factory()->create();
        $userData= $this->makeUserwithDataToTest(['username' => $user->username]);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);
    }

    public function test_username_is_empty(){
        $userData= $this->makeUserwithDataToTest(['username' => '']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);
    }


    public function test_email_has_correct_format(){
        $userData= $this->makeUserwithDataToTest(['email'=> 'usuario@hotmail.com']);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
    }
    
    #[DataProvider('invalidEmails')]

    public function test_email_fails_with_incorrect_format($email): void
    {   
        $userData= $this->makeUserwithDataToTest(['email'=> $email]);
        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_email_is_not_unique_in_database(){

        $user= User::factory()->create();
        $userData= $this->makeUserwithDataToTest(['email'=> $user->email]);
        $response= $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

    }

    public function test_telephone_is_empty (){
        $userData= $this->makeUserwithDataToTest(['telephone'=> '']);
        $response= $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['telephone']);
    }

    public function test_password_is_empty(){
        $userData= $this->makeUserwithDataToTest(['password'=> '', 'password_confirmed'=> '']);
        $response= $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    #[DataProvider('invalidPassword')]
    
    public function test_password_has_invalid_format($password){

        $userData= $this->makeUserwithDataToTest(['password'=> $password, 'password_confirmed'=>$password]);
        $response= $this->postJson('/api/register', $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);

    }

    public function test_user_is_saved_in_database(){
        $user= User::factory()->make();
        $userValid= $user->toArray();
        $$this->postJson('/api/register', $userValid);
        $this->assertDatabaseHas('users', ['email'=> $userValid ['email']]);
        
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

    public static function invalidPassword(){
        return [
            'short'=> ['user'],
            'long'=>['mypasswordiscorrect123456789']
        ];
    }

    private function makeUserwithDataToTest($newData){
        $user= User::factory()->make();
        $userData= array_merge($user->toArray(), $newData);

        return $userData;
    }
}

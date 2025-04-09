<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Laravel\Passport\Token;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ModifyProfileRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $validatedData= $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user= User::create($validatedData);
        
        $token= $user->createToken('Register-token')->accessToken;

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token'=>$token,
        ], 201);
        

    }
    
    public function login(LoginRequest $request){

        $validatedData= $request->validated();
        
        if (Auth::attempt(['password'=> $validatedData['password'], 'email'=>$validatedData['email']])) {
            $user = Auth::user();
            $token = $user->createToken('login-token')->accessToken;
    
            return response()->json([
                'message'=> 'User authenticated',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);
       
    }

    public function checkProfile(){

        $user= Auth::user();
       
        return response()->json([
            'email'=> $user->email,
            'telephone'=>$user->telephone,
            'username'=>$user->username,
            'street_type'=>$user->street_type,
            'street_name'=>$user->street_name,
            'message'=> 'user can check profile'
        ], 200);

    }

    public function modifyProfile(ModifyProfileRequest $request){

        $validatedData= $request->validated();
        $user=Auth::user();
        
        try{
        $user->update($validatedData);
        return response()->json([
            'data'=> $user,
            'message'=> 'User has been modified'
        ]);
        }catch (Exception $e){
            return response()->json([
                'message' => 'User failed modification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
        
    public function logout(LogoutRequest $request){
        $validatedData= $request->validated();
        $user= Auth::user();

    if (($validatedData['email'] == $user->email) && (Hash::check($validatedData['password'], $user->password))){
        $user->token()->revoke();
        $user->token()->delete();
            return response()->json([
            'message'=> 'User is logged out'
             ],200);
    }else {
            return response()->json([
                'message'=> 'User is not correct'
             ],500);
        }

    }


    public function deleteProfile(){


    }

    
}



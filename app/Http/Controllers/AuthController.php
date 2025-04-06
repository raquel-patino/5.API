<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Passport\Token;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RegisterRequest;

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
}

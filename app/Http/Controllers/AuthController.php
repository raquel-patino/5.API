<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Laravel\Passport\Token;

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
}

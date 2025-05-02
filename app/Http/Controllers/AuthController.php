<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Laravel\Passport\Token;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ModifyProfileRequest;


class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "surname", "username", "email", "password", "password_confirmation", "telephone"},
 *             @OA\Property(property="name", type="string", example="Raquel"),
 *             @OA\Property(property="surname", type="string", example="Martínez"),
 *             @OA\Property(property="username", type="string", example="raquel89"),
 *             @OA\Property(property="email", type="string", example="raquel@example.com"),
 *             @OA\Property(property="password", type="string", example="12345678"),
 *             @OA\Property(property="password_confirmation", type="string", example="12345678"),
 *             @OA\Property(property="telephone", type="string", example="612345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User created successfully"),
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="token", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed"
 *     )
 * )
 */


    public function register(RegisterRequest $request){

        $validatedData= $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user= User::create($validatedData);
        
        $token= $user->createToken('Register-token')->accessToken;

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
        

    }

    /**
 * @OA\Post(
 *     path="/login",
 *     summary="Authenticate user and return access token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="raquel@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User authenticated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User authenticated"),
 *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJh..."),
 *             @OA\Property(property="token_type", type="string", example="Bearer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     )
 * )
 */

    
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

    public function index(){

        $user= Auth::user();
       
        return response()->json([
            "user"=> $user,
            'message'=> 'user can check profile'
        ], 200);

    }

    /**
 * @OA\Put(
 *     path="/users",
 *     summary="Update authenticated user's profile",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Raquel"),
 *             @OA\Property(property="surname", type="string", example="Martínez"),
 *             @OA\Property(property="username", type="string", example="raquel89"),
 *             @OA\Property(property="email", type="string", example="raquel@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678"),
 *             @OA\Property(property="street_type", type="string", example="Calle"),
 *             @OA\Property(property="street_name", type="string", example="Mayor"),
 *             @OA\Property(property="postcode", type="string", example="28001"),
 *             @OA\Property(property="city", type="string", example="Madrid"),
 *             @OA\Property(property="country", type="string", example="España"),
 *             @OA\Property(property="telephone", type="string", example="612345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object"),
 *             @OA\Property(property="message", type="string", example="User has been modified")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="User failed modification"
 *     )
 * )
 */

    public function update(ModifyProfileRequest $request){

        $validatedData= $request->validated();
       
        $user=Auth::user();
        
        try{
        if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
                unset($validatedData['password']);
        }
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

/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Logout authenticated user",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User is logged out")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized (invalid or missing token)"
 *     )
 * )
 */

    public function logout(){
        $user= Auth::user();
        $token= $user->token();

        if ($token) {
            $token->revoke();
            $token->delete();
        }
    
        return response()->json([
            'message' => 'User is logged out'
        ], 200);

    }

/**
 * @OA\Delete(
 *     path="/users",
 *     summary="Delete authenticated user's profile",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User deleted profile successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The user has deleted his/her profile")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized (user not authenticated)"
 *     )
 * )
 */
public function destroy() //metodo modificado para que funcione el front
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(["message" => "No autenticado"], 401);
    }

    // Eliminar reservas asociadas
    $user->reservations()->delete();

    $token = $user->token();
    if ($token) {
        $token->revoke();
        $token->delete();
    }

    $user->delete();

    return response()->json([
        "message" => "El usuario ha eliminado su cuenta",
    ], 200);
}

    
}



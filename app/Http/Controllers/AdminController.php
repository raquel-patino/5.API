<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
{
    $users =User::all(); 

    return response()->json([
        'message' => 'Users retrieved successfully',
        'users' => $users
    ]);
}

    public function changeRol($userId){

        $user= User::findOrFail($userId);

        if($user->user_type === 'client'){
            $user->user_type= 'admin';
        }
        return response()->json([
            "user"=> $user,
            "message"=> "Rol changed successfully"
        ],200);
    }

}

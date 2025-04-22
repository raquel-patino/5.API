<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
{
    $users =User::pagiate(10); 

    return response()->json([
        'message' => 'Users retrieved successfully',
        'users' => $users
    ]);
}

public function changeRole(Request $request, $userId)
{
    $request->validate([
        'user_type' => 'required|string|in:admin,client'
    ]);

    $user = User::findOrFail($userId);

    if (Auth::user()->id === $user->id) {
        return response()->json([
            'error' => 'You cannot change your own role.'
        ], 403);
    }

    if ($user->user_type === $request->input('user_type')) {
        return response()->json([
            'message' => 'This user already has the role: ' . $user->user_type
        ], 200);
    }

    $user->user_type = $request->input('user_type');
    $user->save();

    return response()->json([
        'user' => $user,
        'message' => 'Role updated successfully to ' . $user->user_type
    ], 200);
}


}

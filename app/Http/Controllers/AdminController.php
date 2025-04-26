<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
/**
 * @OA\Get(
 *     path="/admin/users",
 *     summary="List all users (Admin only)",
 *     tags={"Admin"},
 *     security={{"Bearer":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Users retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
 *             @OA\Property(
 *                 property="users",
 *                 type="object",
 *                 description="Pagination metadata and user list"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden (not admin)"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function index()
{
    $users =User::paginate(10); 

    return response()->json([
        'message' => 'Users retrieved successfully',
        'users' => $users
    ],200);
}

/**
 * @OA\Patch(
 *     path="/admin/users/{id}/role",
 *     summary="Change a user's role (Admin only)",
 *     tags={"Admin"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user whose role will be updated",
 *         required=true,
 *         @OA\Schema(type="integer", example=6)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_type"},
 *             @OA\Property(
 *                 property="user_type",
 *                 type="string",
 *                 enum={"admin", "client"},
 *                 example="admin"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="message", type="string", example="Role updated successfully to admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden (trying to change own role or not admin)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */


public function update(Request $request, $userId)
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

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
/*
Route::get('/test', function () {
    return response()->json(['status' => 'OK']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
*/

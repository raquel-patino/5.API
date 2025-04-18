<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('/users',[AuthController::class, 'checkProfile']);
    Route::put('/users',[AuthController::class, 'modifyProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/users', [AuthController::class, 'deleteProfile']);
    Route::get('/hotels', [HotelController::class, 'read']);
    Route::get('/hotels/{id}/rooms', [HotelController::class, 'getRooms']);
    Route::post('/reservations', [ReservationController::class, 'create']);
    Route::get('/reservations', [ReservationController::class, 'show']);
});

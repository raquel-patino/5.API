<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('/users',[AuthController::class, 'checkProfile']);
    Route::put('/users',[AuthController::class, 'modifyProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/users', [AuthController::class, 'deleteProfile']);
    Route::post('/reservations', [ReservationController::class, 'create']);
});

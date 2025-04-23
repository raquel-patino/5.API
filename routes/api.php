<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\EnsureUserIsAdmin;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('/users',[AuthController::class, 'checkProfile']);
    Route::put('/users',[AuthController::class, 'modifyProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/users', [AuthController::class, 'deleteProfile']);
    Route::get('/hotels', [HotelController::class, 'getHotels']);
    Route::get('/hotels/{id}/rooms', [HotelController::class, 'getRooms']);
    Route::post('/reservations', [ReservationController::class, 'create']);
    Route::get('/reservations', [ReservationController::class, 'show']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'delete']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::get('/invoices/reservations/{id}', [InvoiceController::class, 'downloadInvoice']);
});



Route::middleware(['auth:api', EnsureUserIsAdmin::class])->group(function(){
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::patch('/admin/users/{id}/role', [AdminController::class, 'changeRole']);
});
   




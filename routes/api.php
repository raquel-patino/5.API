<?php

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
    Route::get('/users',[AuthController::class, 'index']);
    Route::put('/users',[AuthController::class, 'update']);
    Route::delete('/users', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{id}/rooms', [HotelController::class, 'getRooms']);
    Route::resource('reservations', ReservationController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::get('/reservations/{id}/invoices', [InvoiceController::class, 'downloadInvoice']);
});


Route::middleware(['auth:api', EnsureUserIsAdmin::class])->group(function(){
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::patch('/admin/users/{id}/role', [AdminController::class, 'update']);
});
   




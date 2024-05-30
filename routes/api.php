<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MyBookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/Users', [UserController::class, 'store']);

Route::apiResource('Users', UserController::class)->middleware('auth:sanctum')->except('store');
Route::apiResource('Bookings', BookingController::class)->middleware('auth:sanctum');
Route::apiResource('MyBookings', MyBookingController::class)->middleware('auth:sanctum');

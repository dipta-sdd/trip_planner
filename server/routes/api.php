<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\ExpenseController;

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

    Route::get('trips', [TripController::class, 'index']);
    Route::get('trips/{id}', [TripController::class, 'read']);
// Protected routes
Route::middleware('auth:api')->group(function () {

    Route::get('me', [AuthController::class, 'me']);
    Route::get('mytrips', [TripController::class, 'mytrips']);

    
    // Trip participant management

    Route::post('trips', [TripController::class, 'store']);
    Route::post('trips/{trip}/activity', [ActivityController::class, 'store']);
    Route::post('trips/{trip}/activity/{activity}', [ActivityController::class, 'update']);
    Route::delete('trips/{trip}/activity/{activity}', [ActivityController::class, 'destroy']);

    // sponsors
    Route::post('trips/{trip}/sponsor', [SponsorController::class, 'store']);
    Route::delete('trips/{trip}/sponsor/{sponsor}', [SponsorController::class, 'destroy']);
    Route::post('trips/{trip}/sponsor/{sponsor}', [SponsorController::class, 'update']);
    Route::post('trips/{trip}/expense', [ExpenseController::class, 'store']);

    Route::post('trips/{id}/join', [TripController::class, 'join']);
    
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ActivityController;

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Trip routes
    Route::apiResource('trips', TripController::class);
    
    // Trip participant management
    Route::post('trips/{trip}/participants', [TripController::class, 'addParticipant']);
    Route::put('trips/{trip}/participants/{participant}', [TripController::class, 'updateParticipant']);
    Route::delete('trips/{trip}/participants/{participant}', [TripController::class, 'removeParticipant']);
    
    // Trip activity management
    Route::apiResource('trips.activities', ActivityController::class)->shallow();
    Route::get('trips/{trip}/activities/{activity}/details', [ActivityController::class, 'getTypeSpecificDetails']);
});
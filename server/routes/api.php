<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\adminMiddleware;


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

    Route::post('trips', [TripController::class, 'store']);
    Route::put('trips/{id}', [TripController::class, 'update']);
    Route::delete('trips/{id}', [TripController::class, 'destroy']);


    Route::post('trips/{id}/join', [TripController::class, 'join']);
    Route::post('trips/{id}/leave', [TripController::class, 'leave']);
    Route::post('trips/{id}/accept', [TripController::class, 'accept']);
    Route::post('trips/{id}/cancel', [TripController::class, 'cancel']); // cancel request to join trip

    Route::post('trips/{trip}/destinations', [DestinationController::class, 'store']);
    Route::put('trips/{trip}/destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('trips/{trip}/destinations/{destination}', [DestinationController::class, 'destroy']);


    Route::post('trips/{trip}/activities', [ActivityController::class, 'store']);
    Route::put('trips/{trip}/activities/{activity}', [ActivityController::class, 'update']);
    Route::delete('trips/{trip}/activities/{activity}', [ActivityController::class, 'destroy']);

    Route::post('trips/{trip}/accommodations', [App\Http\Controllers\AccommodationController::class, 'store']);
    Route::put('trips/{trip}/accommodations/{accommodation}', [App\Http\Controllers\AccommodationController::class, 'update']);
    Route::delete('trips/{trip}/accommodations/{accommodation}', [App\Http\Controllers\AccommodationController::class, 'destroy']);

    Route::post('trips/{trip}/transportations', [App\Http\Controllers\TransportationController::class, 'store']);
    Route::put('trips/{trip}/transportations/{transportation}', [App\Http\Controllers\TransportationController::class, 'update']);
    Route::delete('trips/{trip}/transportations/{transportation}', [App\Http\Controllers\TransportationController::class, 'destroy']);

    Route::post('trips/{trip}/participants/invite/{user_id}', [App\Http\Controllers\ParticipantController::class, 'invite']);
    Route::post('trips/participants/cancelInvite/{participant_id}', [App\Http\Controllers\ParticipantController::class, 'cancelInvite']);
    Route::post('trips/participants/update/{participant_id}', [App\Http\Controllers\ParticipantController::class, 'update']);
    Route::delete('trips/participants/remove/{participant_id}', [App\Http\Controllers\ParticipantController::class, 'remove']);
    Route::post('trips/participants/makeAdmin/{participant_id}', [App\Http\Controllers\ParticipantController::class, 'makeAdmin']);
    Route::post('trips/participants/removeAdmin/{participant_id}', [App\Http\Controllers\ParticipantController::class, 'removeAdmin']);

    Route::post('trips/{trip}/expenses', [ExpenseController::class, 'store']);
    Route::put('trips/{trip}/expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('trips/{trip}/expenses/{expense}', [ExpenseController::class, 'destroy']);


    Route::post('/comments/{trip}', [CommentController::class, 'store']);

    

    
});


    Route::prefix('admin')->middleware(adminMiddleware::class)->group(function () {
        Route::get('trips', [AdminController::class, 'trips']);
        Route::get('trips/{id}', [AdminController::class, 'trip']);
        Route::put('trips/{id}', [AdminController::class, 'update']);
        Route::delete('trips/{id}', [AdminController::class, 'destroy']);
        Route::get('users', [AdminController::class, 'users']);
        Route::get('users/{id}', [AdminController::class, 'user']);
        Route::get('users/{id}/trips', [AdminController::class, 'userTrips']);
    });

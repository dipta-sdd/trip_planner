<?php

namespace App\Http\Controllers;


use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Transportation;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Exception;

class TransportationController extends Controller
{
    //
    
    public function store(Request $request, $trip)
    {   
        
        $request->validate([
            'destination_id' => 'required',
            'type' => 'required|string',
            'departure_location' => 'nullable|string',
            'arrival_location' => 'nullable|string',
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
            'company' => 'required|string',
            'booking_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        try {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $transportation = Transportation::create([
            'trip_id' => $trip->id,
            'destination_id' => $request->destination_id,
            'type' => $request->type,
            'departure_location' => $request->departure_location,
            'arrival_location' => $request->arrival_location,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'company' => $request->company,
            'booking_reference' => $request->booking_reference,
            'notes' => $request->notes,
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $trip, $transportation){

        $request->validate([
            'destination_id' => 'required',
            'type' => 'required|string',
            'departure_location' => 'nullable|string',
            'arrival_location' => 'nullable|string',
            'departure_time' => 'nullable|date',
            'arrival_time' => 'nullable|date',
            'company' => 'required|string',
            'booking_reference' => 'nullable|string',
            'notes' => 'nullable|string',        
        ]);
        try {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $transportation = Transportation::findOrFail($transportation);
        $transportation->update([
            'trip_id' => $trip->id,
            'destination_id' => $request->destination_id,
            'type' => $request->type,
            'departure_location' => $request->departure_location,
            'arrival_location' => $request->arrival_location,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'company' => $request->company,
            'booking_reference' => $request->booking_reference,
            'notes' => $request->notes,
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($trip, $transportation){
        try {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $transportation = Transportation::findOrFail($transportation);
        $transportation->delete();
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;


use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Activity;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Exception;

class ActivityController extends Controller
{
    //
    
    public function store(Request $request, $trip)
    {   
        
        $request->validate([
            'destination_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable',
        ]);
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $activity = Activity::create([
            'destination_id' => $request->destination_id,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations, 201);
    }

    public function update(Request $request, $trip, $activity)
    {
        $request->validate([
            'destination_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable',
        ]);
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $activity = Activity::findOrFail($activity);
        $activity->update([
            'destination_id' => $request->destination_id,
            'name' => $request->name,
            'type' => $request->type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations);
    }

    public function destroy($trip, $activity)
    {   
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $activity = Activity::findOrFail($activity);
        $activity->delete();
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();

        return response()->json($destinations);
    }
}


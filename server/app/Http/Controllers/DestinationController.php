<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Activity;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    //
    public function store(Request $request, $trip){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'notes' => 'nullable|string',
        ]);
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $destination = Destination::create([
            'trip_id' => $trip->id,
            'name' => $validated['name'],
            'arrival_date' => $validated['arrival_date'],
            'departure_date' => $validated['departure_date'],
            'notes' => $validated['notes'],
        ]);
        return response()->json($destination);
    }
    public function update(Request $request, $trip, $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $destination = Destination::findOrFail($destination);

        $permission = Participant::where('trip_id', $trip->id)
            ->where('user_id', $user->id)
            ->where('can_edit', true)
            ->exists();

        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }

        $destination->update([
            'name' => $validated['name'],
            'arrival_date' => $validated['arrival_date'],
            'departure_date' => $validated['departure_date'],
            'notes' => $validated['notes'],
        ]);

        $destination->save();
        

        return response()->json($destination);
    }

    public function destroy($trip, $destination)
    {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $destination = Destination::findOrFail($destination);

        $permission = Participant::where('trip_id', $trip->id)
            ->where('user_id', $user->id)
            ->where('can_edit', true)
            ->exists();

        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }

        $destination->delete();
        return response()->json(['message' => 'Destination deleted successfully']);
    }
}

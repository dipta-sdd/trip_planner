<?php

namespace App\Http\Controllers;


use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Accommodation;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Exception;

class AccommodationController extends Controller
{
    //
    
    public function store(Request $request, $trip)
    {   
        
        $request->validate([
            'destination_id' => 'required',
            'name' => 'required',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'address' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        try {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $accommodation = Accommodation::create([
            'destination_id' => $request->destination_id,
            'name' => $request->name,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'address' => $request->address,
            'contact_info' => $request->contact_info,
            'notes' => $request->notes
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $trip, $accommodation)
    {
        $request->validate([
            'name' => 'required',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'address' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        try {
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($trip);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $accommodation = Accommodation::findOrFail($accommodation);
        $accommodation->update([
            'name' => $request->name,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'address' => $request->address,
            'contact_info' => $request->contact_info,
            'notes' => $request->notes
        ]);
        $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
        return response()->json($destinations);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($trip, $accommodation)
    {
        try {
            $user = Auth::guard('api')->user();
            $trip = Trip::findOrFail($trip);
            $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
            }
            $accommodation = Accommodation::findOrFail($accommodation);
            $accommodation->delete();
            $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
            return response()->json($destinations);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Activity;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'is_public' => 'boolean',
        ]);
        $user = Auth::guard('api')->user();
        $trip = Trip::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'budget' => $validated['budget'],
            'is_public' => $validated['is_public'],
            'user_id' => $user->id,]);
        
        try {
            $participant = Participant::create([
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'status' => 'accepted',
                'can_edit' => true,
            ]);
            $trip->participant = $participant; 
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
        // Add the creator as a participant with owner role
        
        $trip->can_edit = true;
        // dd(json_encode($user));
        return response()->json($trip, 201);
    }

    public function read(Request $request, $id){
        $trip = Trip::with(['owner', 'activities'])->findOrFail($id);
        $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
        ->join('users', 'participants.user_id', '=', 'users.id')->where('trip_id', $id)->get();
        $trip->participants = $participants;

        $sponsors = Sponsor::where('trip_id', $id)->get();
        $trip->sponsors = $sponsors;

        $activities = Activity::where('trip_id', $id)->get();
        $trip->activities = $activities;
        
        try {
            $user = Auth::guard('api')->user();
            $trip->can_edit = Participant::where('user_id', $user->id)->where('trip_id', $id)->first()->can_edit;
        } catch (\Exception $e) {
            $trip->can_edit = false;
        }
        return response()->json($trip);
    }

} 
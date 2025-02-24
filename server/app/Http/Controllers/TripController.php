<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trips = $user->getAllTrips()->with(['owner', 'participants'])->get();
        
        return response()->json($trips);
    }

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

        $trip = Auth::user()->ownedTrips()->create($validated);

        // Add the creator as a participant with owner role
        $trip->participants()->attach(Auth::id(), [
            'role' => 'owner',
            'status' => 'accepted',
            'can_edit' => true,
        ]);

        return response()->json($trip->load(['owner', 'participants']), 201);
    }

    public function show(Trip $trip)
    {
        $this->authorize('view', $trip);
        
        return response()->json($trip->load(['owner', 'participants', 'activities']));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'is_public' => 'boolean',
            'status' => 'in:planning,ongoing,completed,cancelled',
        ]);

        $trip->update($validated);

        return response()->json($trip->load(['owner', 'participants']));
    }

    public function destroy(Trip $trip)
    {
        $this->authorize('delete', $trip);
        
        $trip->delete();
        
        return response()->json(null, 204);
    }

    public function addParticipant(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'can_edit' => 'boolean',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Check if user is already a participant
        if ($trip->participants()->where('users.id', $user->id)->exists()) {
            return response()->json(['message' => 'User is already a participant'], 422);
        }

        $trip->participants()->attach($user->id, [
            'role' => 'participant',
            'status' => 'pending',
            'can_edit' => $validated['can_edit'] ?? false,
        ]);

        // TODO: Send invitation email to user

        return response()->json($trip->load(['owner', 'participants']));
    }

    public function updateParticipant(Request $request, Trip $trip, User $participant)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'can_edit' => 'boolean',
            'status' => 'in:accepted,declined',
        ]);

        $trip->participants()->updateExistingPivot($participant->id, $validated);

        return response()->json($trip->load(['owner', 'participants']));
    }

    public function removeParticipant(Trip $trip, User $participant)
    {
        $this->authorize('update', $trip);

        if ($trip->user_id === $participant->id) {
            return response()->json(['message' => 'Cannot remove trip owner'], 422);
        }

        $trip->participants()->detach($participant->id);

        return response()->json($trip->load(['owner', 'participants']));
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Trip $trip)
    {
        $this->authorize('view', $trip);
        
        $activities = $trip->activities()
            ->orderBy('start_time')
            ->get();
            
        return response()->json($activities);
    }

    public function store(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|in:activity,transportation,accommodation',
            'cost' => 'nullable|numeric|min:0',
            'details' => 'nullable|json',
        ]);

        $activity = $trip->activities()->create($validated);

        return response()->json($activity, 201);
    }

    public function show(Trip $trip, Activity $activity)
    {
        $this->authorize('view', $trip);
        
        if ($activity->trip_id !== $trip->id) {
            return response()->json(['message' => 'Activity does not belong to this trip'], 404);
        }

        return response()->json($activity);
    }

    public function update(Request $request, Trip $trip, Activity $activity)
    {
        $this->authorize('update', $trip);

        if ($activity->trip_id !== $trip->id) {
            return response()->json(['message' => 'Activity does not belong to this trip'], 404);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'start_time' => 'date',
            'end_time' => 'date|after:start_time',
            'type' => 'in:activity,transportation,accommodation',
            'cost' => 'nullable|numeric|min:0',
            'details' => 'nullable|json',
        ]);

        $activity->update($validated);

        return response()->json($activity);
    }

    public function destroy(Trip $trip, Activity $activity)
    {
        $this->authorize('update', $trip);

        if ($activity->trip_id !== $trip->id) {
            return response()->json(['message' => 'Activity does not belong to this trip'], 404);
        }

        $activity->delete();

        return response()->json(null, 204);
    }

    public function getTypeSpecificDetails(Trip $trip, Activity $activity)
    {
        $this->authorize('view', $trip);

        if ($activity->trip_id !== $trip->id) {
            return response()->json(['message' => 'Activity does not belong to this trip'], 404);
        }

        return response()->json($activity->getTypeSpecificDetails());
    }
} 
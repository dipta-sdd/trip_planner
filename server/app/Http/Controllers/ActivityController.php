<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Activity;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ActivityController extends Controller
{
    public function store(Request $req , $trip){
        try {
        $user = Auth::guard('api')->user();
        $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();

        
        if (!$permission) {
            return response()->json([
                'message' => 'You do not have permission to add an activity to this trip'
            ], 403);
        }
        $data = $req->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required||after:start_time',
            'date' => 'required|date',
            'type' => 'required|string|max:100',
            'cost' => 'nullable|numeric|min:0'
        ]);
        $activity = Activity::create([
            'trip_id' => $trip,
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'date' => $data['date'],
            'type' => $data['type'],
            'cost' => $data['cost'],
        ]);

        return response()->json([
            'message' => 'Activity created successfully',
            'activity' => $activity
            
        ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function update(Request $req, $trip, $activity){
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();

            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to update this activity'
                ], 403);
            }

            $data = $req->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'date' => 'required|date',
                'type' => 'required|string|max:100',
                'cost' => 'nullable|numeric|min:0'
            ]);

            $activity = Activity::findOrFail($activity);
            $activity->update($data);

            return response()->json([
                'message' => 'Activity updated successfully',
                'activity' => $activity
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'message' =>  $e->getMessage()
            ], 500);
        }
    }
    public function destroy($trip, $activity){
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();

            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to delete this activity'
                ], 403);
            }
            $activity = Activity::findOrFail($activity);
            $activity->delete();
            return response()->json([
                'message' => 'Activity deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 
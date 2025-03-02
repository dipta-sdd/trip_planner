<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Trip;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    //admin invite a user
    public function invite($trip, $user_id){
        try
        {
            $user = Auth::guard('api')->user();
            $trip = Trip::findOrFail($trip);
            $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
            }
            $participant = Participant::create([
                'trip_id' => $trip->id,
                'user_id' => $user_id,
                'role' => 'participant',
                'status' => 'invited',
                'can_edit' => false,
            ]);
            return response()->json($participant, 201);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // admin cancel invite
    public function cancelInvite($participant_id ){
        try
        {
            $participant = Participant::findOrFail($participant_id);
            $trip = Trip::findOrFail($participant->trip_id);
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
            }
            $participant->delete();
            return response()->json(['message' => 'Invite canceled successfully'], 201);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // admin update
    public function update(Request $req, $participant_id){
        try{
        $participant = Participant::findOrFail($participant_id);
        $user = Auth::guard('api')->user();
        $trip = Trip::findOrFail($participant->trip_id);
        $permission = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('can_edit', true)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $participant->update($req->all());

        return response()->json($participant, 201);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function remove($participant_id){
        try{
            $participant = Participant::findOrFail($participant_id);
            $user = Auth::guard('api')->user();
            $permission = Trip::where('id', $participant->trip_id)->where('user_id', $user->id)->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
            }
            $participant->delete();
            return response()->json(['message' => 'Participant removed successfully'], 201);
            }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function makeAdmin($participant_id){
        try{
            $participant = Participant::findOrFail($participant_id);
            $user = Auth::guard('api')->user();
            $permission = Trip::where('id', $participant->trip_id)->where('user_id', $user->id)->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
            }
            $participant->can_edit = true;
            $participant->save();
            return response()->json($participant, 201);
            }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

public function removeAdmin($participant_id){
    try{
        $participant = Participant::findOrFail($participant_id);
        $user = Auth::guard('api')->user();
        $permission = Trip::where('id', $participant->trip_id)->where('user_id', $user->id)->exists();
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission to modify this trip.'], 403);
        }
        $participant->can_edit = false;
        $participant->save();
        return response()->json($participant, 201);
    }
    catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

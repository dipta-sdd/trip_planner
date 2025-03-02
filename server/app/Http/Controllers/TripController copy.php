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
        

        $sponsors = Sponsor::where('trip_id', $id)->get();
        $trip->sponsors = $sponsors;

        $activities = Activity::where('trip_id', $id)->get();
        $trip->activities = $activities;
        $trip->is_participant = false;
        try {
            $user = Auth::guard('api')->user();
            $user_participant = Participant::where('user_id', $user->id)->where('trip_id', $id)->first();
            $trip->can_edit = $user_participant->can_edit;
            $trip->participant_status = $user_participant->status;
            $is_participant = Participant::where('user_id', $user->id)->where('trip_id', $id)->exists();
            
        } catch (\Exception $e) {
            $trip->can_edit = false;
        }
        if ($trip->can_edit) {
            
            $expenses = DB::table('expenses')->selectRaw('expenses.*, users.name as name')
            ->join('users', 'expenses.created_by', '=', 'users.id')->where('trip_id', $id)->get();
            $trip->expenses = $expenses;
        }
        if ($trip->is_participant) {
            $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
            ->join('users', 'participants.user_id', '=', 'users.id')->where('trip_id', $id)->get();
            $trip->participants = $participants;
            $avg_spent = DB::table('expenses')->selectRaw('SUM(amount) / (SELECT COUNT( participants.id) FROM participants WHERE participants.trip_id = '.$id . ' ) as sum' )
            ->where('trip_id', $id)->first();
            $trip->avg_spent = $avg_spent->sum;
            $trip->statistics = DB::table('expenses')->selectRaw('SUM(amount) as sum, COUNT(id) as count, category')->where('trip_id', $id)->groupBy('category')->get();
        } else {
            $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
            ->join('users', 'participants.user_id', '=', 'users.id')->where('trip_id', $id)->where('participants.status', 'accepted')->get();
            $trip->participants = $participants;
        }
        return response()->json($trip);
    }

    public function index(Request $req){
        $user = Auth::guard('api')->user();
        if ($user) {
            $trips = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count from trips left join participants on (trips.id = participants.trip_id and participants.status = "accepted") group by trips.id) as trips left join participants on ( trips.id = participants.trip_id and participants.user_id = '.$user->id.' )'))->selectRaw('trips.*, participants.status as participant_status');
        }else{
            $trips = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count from trips left join participants on (trips.id = participants.trip_id and participants.status = "accepted")  group by trips.id) as trips'))->selectRaw('trips.*');
        }
        if($req->query('status')){
            $trips = $trips->whereRaw('trips.status = "'. $req->query('status') .'"');
        }
        if($req->query('start_date')){
            // dd($req->query('start_date'));
            $trips = $trips->whereRaw('trips.start_date >= "'. $req->query('start_date') .'"');
        }
        if($req->query('end_date')){
            $trips = $trips->whereRaw(' trips.end_date <= "'. $req->query('end_date') .'"');
        }
        
        $trips = $trips->get();
        return response()->json($trips);
    }
    
    public function mytrips(Request $req){
        $user = Auth::guard('api')->user();
       
        $trips = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count from trips left join participants on (trips.id = participants.trip_id and participants.status = "accepted") group by trips.id) as trips join participants on ( trips.id = participants.trip_id and participants.user_id = '.$user->id.' )'))->selectRaw('trips.*, participants.status as participant_status, participants.can_edit');
        if($req->query('status')){
            $trips = $trips->whereRaw('trips.status = "'. $req->query('status') .'"');
        }
        if($req->query('start_date')){
            // dd($req->query('start_date'));
            $trips = $trips->whereRaw('trips.start_date >= "'. $req->query('start_date') .'"');
        }
        if($req->query('end_date')){
            $trips = $trips->whereRaw(' trips.end_date <= "'. $req->query('end_date') .'"');
        }
        
        $trips = $trips->get();
        
        return response()->json($trips);
    }


    public function join(Request $request, $id){
        $trip = Trip::findOrFail($id);
        $user = Auth::guard('api')->user();
        $participant = Participant::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'role' => 'participant',
            'status' => 'pending',
            'can_edit' => false,
        ]);
        $trip->participant = $participant; 
        return response()->json($trip, 201);
    }


    public function update(Request $request, $id){
        $trip = Trip::findOrFail($id);
        $user = Auth::guard('api')->user();
        if ($trip->user_id != $user->id) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $trip->update($request->all());
        return response()->json($trip, 200);
    }
    public function destroy($id){
        $trip = Trip::findOrFail($id);
        $user = Auth::guard('api')->user();
        if ($trip->user_id != $user->id) {
            return response()->json(['error' => 'You do not have permission modify this trip.'], 403);
        }
        $trip->delete();
        return response()->json(['message' => 'Trip deleted successfully']);
    }

} 
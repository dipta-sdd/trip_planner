<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\Transportation;
use App\Models\Activity;
use App\Models\Sponsor;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

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
        $user = Auth::guard('api')->user();
        if ($user) {
            $trip = DB::table(DB::raw('(select trips.* , 
            count(participants.id) as participant_count ,
            IF(p.status = "accepted", 
                    true, 
                    false
                ) as is_participant,
            IF(p.status = "accepted", 
                    p.can_edit, 
                    false
                ) as can_edit,
            IF(p.status = "accepted", 
                    IF(p.role = "owner",
                        true,
                        false
                    ),
                    false
                ) as is_creator,
            IF(p.status = "accepted", 
                    IF(p.role = "owner",
                        "admin",
                        IF(p.can_edit, "admin", "participant")
                    ),
                    IF(p.can_edit, "admin", "participant")
                ) as role,
            p.status = "invited" as is_invited,
            p.status as participant_status

            
            from trips 
            left join participants 
            on (trips.id = participants.trip_id and participants.status = "accepted") 
            left join participants as p 
            on (trips.id = p.trip_id and p.user_id = '.$user->id.')
            group by trips.id ) 
            as trips'))
            ->where('id', $id)->first();
            if($trip->is_participant || $trip->is_invited || $trip->is_public){
                $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
                ->join('users', 'participants.user_id', '=', 'users.id')
                ->where('trip_id', $trip->id)->whereIn('status',['accepted', 'invited', 'pending'])
                ->orderBy('status', 'asc')->get();
                $suggested_participants = DB::table(DB::raw( '(SELECT users.* , p.id as p_id FROM users left join participants as p on (p.trip_id = '.$trip->id.' and p.user_id = users.id) where p.id is null) as t'))->get();
                $trip->suggested_participants = $suggested_participants;
                $trip->participants = $participants;
                $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
                $trip->destinations = $destinations;
            }

            if($trip->is_participant){
                $expenses = DB::table('expenses')->selectRaw('expenses.*, users.name as name')
                ->join('users', 'expenses.created_by', '=', 'users.id')->where('trip_id', $trip->id)->get();
                $trip->expenses = $expenses;
                $expense_stat_cat = Expense::selectRaw('category, sum(amount) as amount')
                    ->where('trip_id', $trip->id)->groupBy('category')->get();
                $trip->expense_stat_cat = $expense_stat_cat;
                $expense_stat_date = Expense::selectRaw('date, sum(amount) as amount')
                    ->where('trip_id', $trip->id)->groupBy('date')->get();
                $trip->expense_stat_date = $expense_stat_date;

                $trip->total_expenses = Expense::where('trip_id', $trip->id)->sum('amount');
                $comments = DB::table(DB::raw('(select comments.* , users.name from comments join users on comments.user_id = users.id ) as t'))->where('trip_id', $trip->id)->get();
                $trip->comments = $comments;
            }
            
        }
        else{
            $trip = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count from trips left join participants on (trips.id = participants.trip_id and participants.status = "accepted") group by trips.id ) as trips'))->where('id', $id)->first();
            if($trip->is_public){
                $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
                ->join('users', 'participants.user_id', '=', 'users.id')
                ->where('trip_id', $trip->id)->where('status', 'accepted')
                ->orderBy('status', 'asc')->get();
                $trip->participants = $participants;
                $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
                $trip->destinations = $destinations;
            }
        }
        return response()->json($trip);
    }

    public function index(Request $req){
        $sort = $req->query('sort');
        $order_by = $sort ? explode('-', $sort)[0] : 'start_date';
        $sortDirection = $sort ? explode('-', $sort)[1] : 'asc';
        $user = Auth::guard('api')->user();
        if ($user) {
            $trips = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count ,
            IF(p.status = "accepted", 
                IF(p.role = "owner",
                    "owner",
                    IF(p.can_edit, "admin", "participant") 
                ),
                IF(p.status = "invited",
                    "invited",
                    IF(p.status = "pending",
                        "requested",
                        IF(p.status = "declined",
                            "declined", 
                            IF(p.status = "rejected",
                                "invited",
                                "can_join"
                            )
                        ) 
                    )
                )
            ) as participant_status

            from trips 
            left join participants on (trips.id = participants.trip_id and participants.status = "accepted")
            left join participants as p 
                on (trips.id = p.trip_id and p.user_id = '.$user->id.')
            group by trips.id) as trips left join participants on ( trips.id = participants.trip_id and participants.user_id = '.$user->id.' )'))->selectRaw('trips.*');
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
        $trips = $trips->orderBy($order_by, $sortDirection);
        $trips = $trips->get();
        return response()->json($trips);
    }
    
    public function mytrips(Request $req){
        $sort = $req->query('sort');
        $order_by = $sort ? explode('-', $sort)[0] : 'start_date';
        $sortDirection = $sort ? explode('-', $sort)[1] : 'asc';
        $user = Auth::guard('api')->user();
       
        $trips = DB::table(DB::raw('(select trips.* , count(participants.id) as participant_count ,
            IF(p.status = "accepted", 
                IF(p.role = "owner",
                    "owner",
                    IF(p.can_edit, "admin", "participant") 
                ),
                IF(p.status = "invited",
                    "invited",
                    IF(p.status = "pending",
                        "requested",
                        IF(p.status = "declined",
                            "declined", 
                            IF(p.status = "rejected",
                                "invited",
                                "can_join"
                            )
                        ) 
                    )
                )
            ) as participant_status

            from trips 
            left join participants on (trips.id = participants.trip_id and participants.status = "accepted")
            join participants as p 
                on (trips.id = p.trip_id and p.user_id = '.$user->id.')
            group by trips.id) as trips '))->selectRaw('trips.*');
        if($req->query('status')){
            if(in_array($req->query('status'), ['planning', 'completed', 'canceled'])){
                $trips = $trips->whereRaw('trips.status = "'. $req->query('status') .'"');
            } else {
                $trips = $trips->whereRaw('trips.participant_status = "'. $req->query('status') .'"');
            }
        }
        if($req->query('start_date')){
            // dd($req->query('start_date'));
            $trips = $trips->whereRaw('trips.start_date >= "'. $req->query('start_date') .'"');
        }
        if($req->query('end_date')){
            $trips = $trips->whereRaw(' trips.end_date <= "'. $req->query('end_date') .'"');
        }
        
        $trips = $trips->orderBy($order_by, $sortDirection)->get();

        $invites = DB::table(DB::raw('( select 
            t.id as trip_id, t.title as trip_name, p.id, p.user_id , p.invited_by, u.name as invited_by_name, p.created_at as invited_at
            from participants as p 
            join trips as t on (p.trip_id = t.id and p.status = "planning") 
            join users as u on p.invited_by = u.id
            where p.status = "invited" and p.user_id = '.$user->id.' 
            ) as i'))->get();


        
        return response()->json(['trips' => $trips, 'invites' => $invites]);
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

    public function leave(Request $request, $id){
        $user = Auth::guard('api')->user();
        try {
            $trip = Trip::where('id', $id)->where('status', 'planning')->firstOrFail();
            $participant = Participant::where('trip_id', $trip->id)->where('user_id', $user->id)->where('role', 'participant')->firstOrFail();
            $participant->delete();
            return response()->json(['message' => 'Trip leaved successfully']);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function accept(Request $request, $id){
        $user = Auth::guard('api')->user();
        try {
            $trip = Trip::where('id', $id)->where('status', 'planning')->firstOrFail();
            $participant = Participant::where('trip_id', $id)->where('user_id', $user->id)->where('role', 'participant')->where('status', 'invited')->firstOrFail();
            $participant->status = 'accepted';
            $participant->save();
            return response()->json(['message' => 'Trip accepted successfully']);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function cancel(Request $request, $id){
        $user = Auth::guard('api')->user();
        try {
            $trip = Trip::where('id', $id)->where('status', 'planning')->firstOrFail();
            
            $participant = Participant::where('trip_id', $id)->where('user_id', $user->id)->where('role', 'participant')->where('status', 'pending')->firstOrFail();
            $participant->delete();
            return response()->json(['message' => 'Trip join request cancelled successfully']);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
} 
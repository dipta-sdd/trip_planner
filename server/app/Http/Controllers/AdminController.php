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

use Exception;

class AdminController extends Controller
{
    //

    public function trips(Request $req){
        $per_pqge = $req->per_page ?? 10;
        $sort = $req->sort ?? 'title';
        $sortDirection = $req->sortDirection ?? 'asc';
        $trips = DB::table(DB::raw('(select trips.*, owner.name as owner , count(participants.id) as participant_count , sum(expenses.amount) as total_expense
        from trips 
        left join participants on (trips.id = participants.trip_id and participants.status = "accepted") 
        left join users as owner on (trips.user_id = owner.id)
        left join expenses on (trips.id = expenses.trip_id)
        group by trips.id) as trips'));

        if($req->query('status')){
            $trips = $trips->where('status', $req->query('status'));
        }

        if($req->query('search')){
            $trips = $trips->where('title', 'like', '%'.$req->query('search').'%');
        }

        if($req->query('start_date')){
            $trips = $trips->where('start_date', '>=', $req->query('start_date'));
        }
        if($req->query('end_date')){
            $trips = $trips->where('end_date', '<=', $req->query('end_date'));
        }
        if($req->query('min_budget')){
            $trips = $trips->where('budget', '>=', $req->query('min_budget'));
        }
        if($req->query('max_budget')){
            $trips = $trips->where('budget', '<=', $req->query('max_budget'));
        }
        
        
        $trips = $trips->orderBy($sort, $sortDirection)->paginate($per_pqge);
        return response()->json($trips);
    }
    public function userTrips(Request $req, $id){
        $per_pqge = $req->per_page ?? 10;
        $sort = $req->sort ?? 'title';
        $sortDirection = $req->sortDirection ?? 'asc';
        $trips = DB::table(DB::raw('(select trips.*, owner.name as owner , count(participants.id) as participant_count , sum(expenses.amount) as total_expense
        from trips 
        left join participants on (trips.id = participants.trip_id and participants.status = "accepted")
        join participants as p on (trips.id = p.trip_id  and p.status = "accepted" and p.user_id = '.$id.') 
        left join users as owner on (trips.user_id = owner.id)
        left join expenses on (trips.id = expenses.trip_id)
        group by trips.id) as trips'));

        if($req->query('status')){
            $trips = $trips->where('status', $req->query('status'));
        }

        if($req->query('search')){
            $trips = $trips->where('title', 'like', '%'.$req->query('search').'%');
        }

        if($req->query('start_date')){
            $trips = $trips->where('start_date', '>=', $req->query('start_date'));
        }
        if($req->query('end_date')){
            $trips = $trips->where('end_date', '<=', $req->query('end_date'));
        }
        if($req->query('min_budget')){
            $trips = $trips->where('budget', '>=', $req->query('min_budget'));
        }
        if($req->query('max_budget')){
            $trips = $trips->where('budget', '<=', $req->query('max_budget'));
        }
        
        
        $trips = $trips->orderBy($sort, $sortDirection)->paginate($per_pqge);
        return response()->json($trips);
    }

    public function trip($id){ 
        $trip = DB::table(DB::raw('(select trips.* , 
            count(participants.id) as participant_count 
            from trips 
            left join participants 
            on (trips.id = participants.trip_id and participants.status = "accepted") 
            
            group by trips.id ) 
            as trips'))
            ->where('id', $id)->first();
            $participants = Participant::selectRaw('participants.*, users.name as name, users.email as email')
                ->join('users', 'participants.user_id', '=', 'users.id')
                ->where('trip_id', $trip->id)->whereIn('status',['accepted', 'invited', 'pending'])
                ->orderBy('status', 'asc')->get();
            $trip->participants = $participants;
            $destinations = Destination::with(['activities', 'accommodations', 'transportations'])->where('trip_id', $trip->id)->get();
            $trip->destinations = $destinations;
            $expenses = DB::table('expenses')->selectRaw('expenses.*, users.name as name')
            ->join('users', 'expenses.created_by', '=', 'users.id')->where('trip_id', $trip->id)->get();
            $trip->expenses = $expenses;

        return response()->json($trip);
    }

    public function update(Request $request, $id){
        $trip = Trip::findOrFail($id);
        
        $trip->update($request->all());
        return response()->json($trip, 200);
    }

    public function destroy($id){
        $trip = Trip::findOrFail($id);
        
        $trip->delete();
        return response()->json(['message' => 'Trip deleted successfully']);
    }

    public function users(Request $req){
// sleep(2);

        $per_pqge = $req->per_page ?? 10;
        $sort = $req->sort ?? 'name';
        $sortDirection = $req->sortDirection ?? 'asc';
        $users = DB::table(DB::raw('(select users.* , count(participants.id) as trips, count(trips.id) as trip_owned
        from users 
        left join participants on (users.id = participants.user_id and participants.status = "accepted") 
        left join trips on (users.id = trips.user_id)
        group by users.id) as users'));
        if($req->query('search')){
            $users = $users->where('name', 'like', '%'.$req->query('search').'%');
        }
        if($req->query('ustatus')){
            $users = $users->where('ustatus', $req->query('ustatus'));
        }
        $users = $users->orderBy($sort, $sortDirection)->paginate($per_pqge);
        return response()->json($users);
    }
    public function user($id){
        $user =  DB::table(DB::raw('(select users.* , count(participants.id) as trips, count(trips.id) as trip_owned
        from users 
        left join participants on (users.id = participants.user_id and participants.status = "accepted") 
        left join trips on (users.id = trips.user_id)
        group by users.id) as users'))->where('id', $id)->first();
        return response()->json($user); 
    }
}

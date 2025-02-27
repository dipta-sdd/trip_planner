<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use App\Models\Expense;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
class ExpenseController extends Controller
{
    //
    public function store(Request $req , $trip)
    {
        $data = $req->validate([
            'amount' => 'required|numeric|gt:0',
            'note' => 'nullable|string',
            'date' => 'required|date',
            'category' => 'required|in:Accommodation,Food,Transportation,Activities,Entertainment,Emergency,Miscellaneous'
        ]);
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to add an activity to this trip'
                ], 403);
            }
            $expense = Expense::create([
                'amount' => $data['amount'],
                'trip_id' => $trip,
                'note' => $data['note'],
                'date' => $data['date'],
                'category' => $data['category'],
                'created_by' => $user->id
            ]);
            $expenses = DB::table('expenses')->selectRaw('expenses.*, users.name as name')
                ->join('users', 'expenses.created_by', '=', 'users.id')->where('trip_id', $trip)->get();
            return response()->json([
                'message' => 'Expense created successfully',
                'expense' => $expense,
                'expenses' => $expenses
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


}

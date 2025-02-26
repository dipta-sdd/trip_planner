<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Activity;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Exception;


class SponsorController extends Controller
{
    public function store(Request $req, $trip){
        $data = request()->validate([
                'name' => 'required|string|max:255',
                'website' => 'nullable|string',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|gt:0',
            ]);
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();

            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to add an activity to this trip'
                ], 403);
            }
            
            $sponsor = Sponsor::create([
                'trip_id' => $trip,
                'name' => $data['name'],
                'website' => $data['website'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            return response()->json([
                'message' => 'Sponsor created successfully',
                'sponsor' => $sponsor
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($trip, $sponsor){
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to delete an activity to this trip'
                ], 403);
            }
            Sponsor::where('id', $sponsor)->delete();
            return response()->json([
                'message' => 'Sponsor deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, $trip, $sponsor){
        $data = request()->validate([
                'name' => 'required|string|max:255',
                'website' => 'nullable|string',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|gt:0',
            ]);
        try {
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('can_edit', true)->exists();
            if (!$permission) {
                return response()->json([
                    'message' => 'You do not have permission to update an activity to this trip'
                ], 403);
            }
            Sponsor::where('id', $sponsor)->update([
                'name' => $data['name'],
                'website' => $data['website'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'updated_by' => $user->id,
            ]);
            return response()->json([
                'message' => 'Sponsor updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

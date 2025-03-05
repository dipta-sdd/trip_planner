<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Participant;
use App\Models\Comment;
use App\Models\Accommodation;
use App\Models\Transportation;
use App\Models\Activity;
use App\Models\Sponsor;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CommentController extends Controller
{
    public function store(Request $request, $trip)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        try{
            $user = Auth::guard('api')->user();
            $permission = Participant::where('trip_id', $trip)->where('user_id', $user->id)->where('status', 'accepted')->exists();
            if (!$permission) {
                return response()->json(['error' => 'You do not have permission comment on this trip.'], 403);
            }
            $comment = Comment::create([
                'trip_id' => $trip,
                'user_id' => $user->id,
                'text' => $validated['text'],
                'parent_id' => $validated['parent_id'],
            ]);
            $comment->name = $user->name;
            return response()->json($comment, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = Auth::guard('api')->user();
            $comment = Comment::findOrFail($id);

            // Check if the user has permission to delete the comment
            $permission = Participant::where('trip_id', $comment->trip_id)
                ->where('user_id', $user->id)
                ->where('can_edit', true)
                ->exists();

            if (!$permission && $comment->user_id !== $user->id) {
                return response()->json(['error' => 'You do not have permission to delete this comment.'], 403);
            }

            $comment->delete();

            return response()->json(['message' => 'Comment deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function togglePostUpvote($id)
    {
        $vote = Vote::where([
            'post_id' => $id,
            'user_id' => Auth::id()
        ])->first();
        $post = Post::findOrFail($id);

        // Vote doesnt exist
        if ($vote == NULL) {
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => 1,
            ]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
        // Is upvoted
        else if ($vote->vote == 1) {
            $vote->update(['vote' => 0]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
        // Is downvoted
        else {
            $vote->update(['vote' => 1]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
    }

    public function togglePostDownvote($id)
    {
        $vote = Vote::where([
            'post_id' => $id,
            'user_id' => Auth::id()
        ])->first();
        $post = Post::findOrFail($id);

        // Vote doesnt exist
        if ($vote == NULL) {
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => -1
            ]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
        // Is downvoted
        else if ($vote->vote == -1) {
            $vote->update(['vote' => 0]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
        // Is upvoted
        else {
            $vote->update(['vote' => -1]);
            $post->refresh();
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $post->getVoteCountAttribute()
            ]);
        }
    }
}

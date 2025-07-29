<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentVoteController extends Controller
{
    public function toggleCommentUpvote($id){
        $vote = CommentVote::where([
            'comment_id' => $id,
            'user_id' => Auth::id()
        ])->first();
        $comment = Comment::findOrFail($id);
        
        // Vote doesnt exist
        if($vote == NULL){
            CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'vote' => 1
            ]);
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
        // Is upvoted
        else if($vote->vote == 1){
            $vote->update(['vote' => 0]);
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
        // else, update vote = 1
        else {
            $vote->update(['vote' => 1]);
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
    }
    
    public function toggleCommentDownvote($id){
        $vote = CommentVote::where([
            'comment_id' => $id,
            'user_id' => Auth::id()
        ])->first();
        $comment = Comment::findOrFail($id);

        // Vote doesnt exist
        if($vote == NULL){
            CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'vote' => -1
            ]);
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
        // Is downvoted
        else if($vote->vote == -1){
            $vote->update(['vote' => 0]);
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
        // Is upvoted
        else {
            $vote->update(['vote' => -1]);
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $comment->getVoteCountAttribute()
            ]);
        }
    }
}

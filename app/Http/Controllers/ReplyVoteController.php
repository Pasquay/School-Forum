<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\ReplyVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyVoteController extends Controller
{
    public function toggleReplyUpvote($id){
        $vote = ReplyVote::where([
            'reply_id' => $id,
            'user_id' => Auth::id()
        ])->first();
        $reply = Reply::findOrFail($id);

        // Vote doesnt exist
        if($vote == NULL){
            ReplyVote::create([
                'reply_id' => $id,
                'user_id' => Auth::id(),
                'vote' => 1
            ]);
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $reply->getVoteCountAttribute(),
            ]);
        }
        // Is upvoted
        else if($vote->vote == 1){
            $vote->update(['vote' => 0]);
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $reply->getVoteCountAttribute(),
            ]);
        }
        // else
        else{
            $vote->update(['vote' => 1]);
            return response()->json([
                'success' => true,
                'voteValue' => 1,
                'voteCount' => $reply->getVoteCountAttribute(),
            ]);
        }
    }

    public function toggleReplyDownvote($id){
        $vote = ReplyVote::where([
            'reply_id' => $id,
            'user_id' => Auth::id(),
        ])->first();
        $reply = Reply::findOrFail($id);
        
        // Vote doesnt exist
        if($vote == NULL){
            ReplyVote::create([
                'reply_id' => $id,
                'user_id' => Auth::id(),
                'vote' => -1
            ]);
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $reply->getVoteCountAttribute(), 
            ]);
        }
        // Is downvoted
        else if($vote->vote == -1){
            $vote->update(['vote' => 0]);
            return response()->json([
                'success' => true,
                'voteValue' => 0,
                'voteCount' => $reply->getVoteCountAttribute(),
            ]);
        }
        // else
        else{
            $vote->update(['vote' => -1]);
            return response()->json([
                'success' => true,
                'voteValue' => -1,
                'voteCount' => $reply->getVoteCountAttribute(),
            ]);
        }
    }
}

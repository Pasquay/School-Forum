<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function getReplies($id){
        $replies = Reply::where('comment_id', $id)
            ->with('user')
            ->get()
            ->map(function($reply){
                $reply->votes = $reply->getVoteCountAttribute();
                $reply->userVote = $reply->getUserVoteAttribute();
                return $reply;
            });
        return response()->json([
            'success' => true,
            'replies' => $replies,
        ]);
    }

    public function create($postId, $commentId, Request $request){
        $replyData = $request->validate([
            'create-reply-content-' . $commentId => ['required']
        ]);
        Reply::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'content' => $replyData['create-reply-content-' . $commentId],
        ]);
        return redirect('/post/' . $postId)->with('success', 'Reply created successfully');
    }

    public function edit($postId, $replyId, Request $request){
        $reply = Reply::findOrFail($replyId);
        if($reply->user_id == Auth::id()){
            $replyData = $request->validate([
                'edit-reply-content-' . $replyId => ['required']
            ]);
            $reply->update([
                'content' => $replyData['edit-reply-content-' . $replyId]
            ]);
            return redirect('/post/' . $postId)->with('success', 'Reply edited successfully');
        } else {
            return redirect('/post/' . $postId)->with('error', 'Invalid credentials');
        }
    }

    public function delete($postId, $replyId, Request $request){
        $reply = Reply::findOrFail($replyId);
        if($reply->user_id == Auth::id()){
            $reply->delete();
            return redirect('/post/' . $postId)->with('success', 'Reply deleted successfully');
        } else {
            return redirect('/post/' . $postId)->with('error', 'Invalid credentials');
        }
    }

    public function getUserRepliesData($userId) {
        $replies = Reply::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->withCount(['votes'])
            ->with([
                'comment' => function($query){
                    $query->withTrashed();
                },
                'comment.user',
                'comment.post' => function($query){
                    $query->withTrashed();
                },
                'comment.post.user',
            ])
            ->get();
        
        return $replies->transform(function($reply){
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });
    }

    public function getUserReply($userId){
        $replies = $this->getUserRepliesData($userId);

        return response()->json([
            'replies' => $replies
        ]);
    }
}

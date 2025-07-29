<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create($postId, Request $request){
        $commentData = $request->validate([
            'create-comment-content' => ['required']
        ]);
        Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $commentData['create-comment-content'],
        ]);

        return redirect('/post/' . $postId)->with('success', 'Comment created successfully');
    }

    public function edit($postId, $commentId, Request $request){
        $comment = Comment::findOrFail($commentId);
        if($comment->user_id == Auth::id()){
            $commentData = $request->validate([
                'edit-comment-content-' . $commentId => ['required']
            ]);
            $comment->update([
                'content' => $commentData['edit-comment-content-' . $commentId]
            ]);
            return redirect('/post/' . $postId)->with('success', 'Comment edited successfully');
        } else {
            return redirect('/post/' . $postId)->with('error', 'Invalid credentials');
        }
    }

    public function delete($postId, $commentId, Request $request){
        $comment = Comment::findOrFail($commentId);
        if($comment->user_id == Auth::id()){
            $comment->delete();
            return redirect('/post/' . $postId)->with('success', 'Comment deleted successfully');
        } else {
            return redirect('/post/' . $postId)->with('error', 'Invalid credentials');
        }
    }

    public function getUserComment($userId, Request $request){
        $comments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->withCount(['votes'])
            ->with(['post', 'post.user'])
            ->paginate(15);

        $comments->getCollection()->transform(function($comment){
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            return $comment;
        });

        $html = '';
        foreach($comments as $comment){
            $html .= view('components.profile-comment', compact('comment'))->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $comments->currentPage() < $comments->lastPage() ? $comments->currentPage()+1 : NULL,
        ]);
    }
}

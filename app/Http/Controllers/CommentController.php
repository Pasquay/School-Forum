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

    public function getUserCommentsData($userId) {
        $comments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->withCount(['votes'])
            ->with(['post' => function($query){
                $query->withTrashed();
            }, 'post.user'])
            ->get();

        return $comments->transform(function($comment){
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });
    }


    public function getUserComment($userId, Request $request){
        $comments = $this->getUserCommentsData($userId);

        return response()->json([
            'comments' => $comments
        ]);

        // $html = '';
        // foreach($comments as $comment){
        //     $html .= view('components.profile-comment', compact('comment'))->render();
        // }
        // return response()->json([
        //     'html' => $html,
        //     'next_page' => $comments->currentPage() < $comments->lastPage() ? $comments->currentPage()+1 : NULL,
        // ]);
    }

    public function getUserDeletedComments(Request $request, $userId){
        $deletedComments = Comment::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->withCount(['votes'])
            ->with(['post' => function($query){
                $query->withTrashed();
            }, 'post.user'])
            ->paginate(15);

        $deletedComments->getCollection()->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            return $comment;
        });

        $html = '';
        foreach($deletedComments as $deletedComment){
            $html .= view('components.profile-comment', ['comment' => $deletedComment])->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $deletedComments->currentPage() < $deletedComments->lastPage() ? $deletedComments->currentPage()+1 : NULL,
        ]);
    }

    public function restore($id){
        $comment = Comment::onlyTrashed()
            ->with(['post' => function($query){
                $query->withTrashed();
            }])
            ->findOrFail($id);
        if($comment->user_id === Auth::id() && $comment->deleted_at && !$comment->post->deleted_at){
            $comment->restore();
            return redirect('/post/' . $comment->post->id . '#comment-' . $comment->id)->with('success', 'Comment restored successfully');
        } else if($comment->user_id != Auth::id()){
            return redirect('/user/' . Auth::id())->with('error', 'Invalid Credentials');
        } else if($comment->post->deleted_at != NULL){
            return redirect('/user/' . Auth::id())->with('error', 'Cannot restore to a deleted post');
        } else {
            return redirect('/user/' . Auth::id())->with('error', 'Comment must be deleted to be restored');
        }
    }
}

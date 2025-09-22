<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create($postId, Request $request)
    {
        $commentData = $request->validate([
            'create-comment-content' => ['required']
        ]);
        Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $commentData['create-comment-content'],
        ]);

        return redirect()->back()->with('success', 'Comment created successfully');
    }

    public function edit($postId, $commentId, Request $request)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id == Auth::id()) {
            $commentData = $request->validate([
                'edit-comment-content-' . $commentId => ['required']
            ]);
            $comment->update([
                'content' => $commentData['edit-comment-content-' . $commentId]
            ]);
            return redirect()->back()->with('success', 'Comment edited successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }



    public function getUserCommentsData($userId)
    {
        $comments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->withCount(['votes'])
            ->with(['post' => function ($query) {
                $query->withTrashed();
            }, 'post.user'])
            ->get();

        return $comments->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });
    }

    public function getUserComment($userId, Request $request)
    {
        $comments = $this->getUserCommentsData($userId);

        return response()->json([
            'comments' => $comments
        ]);
    }

    public function getUserDeletedCommentsData($userId)
    {
        $deletedComments = Comment::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->withCount(['votes'])
            ->with([
                'post' => function ($query) {
                    $query->withTrashed();
                },
                'post.user'
            ])
            ->get();

        return $deletedComments->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });
    }

    public function getUserDeletedComments($userId)
    {
        $deletedComments = $this->getUserDeletedCommentsData($userId);

        return response()->json([
            'deletedComments' => $deletedComments
        ]);
    }

    public function delete($postId, $commentId, Request $request)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id == Auth::id()) {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function restore($id)
    {
        $comment = Comment::onlyTrashed()
            ->with(['post' => function ($query) {
                $query->withTrashed();
            }])
            ->findOrFail($id);

        if ($comment->user_id === Auth::id() && $comment->deleted_at && !$comment->post->deleted_at) {
            $comment->restore();
            return redirect()->back()->with('success', 'Comment restored successfully');
        } else if ($comment->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Invalid Credentials');
        } else if ($comment->post->deleted_at != NULL) {
            return redirect()->back()->with('error', 'Cannot restore to a deleted post');
        } else {
            return redirect()->back()->with('error', 'Comment must be deleted to be restored');
        }
    }
}

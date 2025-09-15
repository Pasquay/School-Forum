<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function getReplies($id)
    {
        $replies = Reply::where('comment_id', $id)
            ->with('user')
            ->get()
            ->map(function ($reply) {
                $reply->votes = $reply->getVoteCountAttribute();
                $reply->userVote = $reply->getUserVoteAttribute();
                return $reply;
            });
        return response()->json([
            'success' => true,
            'replies' => $replies,
        ]);
    }

    public function create($postId, $commentId, Request $request)
    {
        $replyData = $request->validate([
            'create-reply-content-' . $commentId => ['required']
        ]);
        Reply::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'content' => $replyData['create-reply-content-' . $commentId],
        ]);
        return redirect()->back()->with('success', 'Reply created successfully');
    }

    public function edit($postId, $replyId, Request $request)
    {
        $reply = Reply::findOrFail($replyId);
        if ($reply->user_id == Auth::id()) {
            $replyData = $request->validate([
                'edit-reply-content-' . $replyId => ['required']
            ]);
            $reply->update([
                'content' => $replyData['edit-reply-content-' . $replyId]
            ]);
            return redirect()->back()->with('success', 'Reply edited successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function delete($postId, $replyId, Request $request)
    {
        $reply = Reply::findOrFail($replyId);
        if ($reply->user_id == Auth::id()) {
            $reply->delete();
            return redirect()->back()->with('success', 'Reply deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function getUserRepliesData($userId)
    {
        $replies = Reply::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->withCount(['votes'])
            ->with([
                'comment' => function ($query) {
                    $query->withTrashed();
                },
                'comment.user',
                'comment.post' => function ($query) {
                    $query->withTrashed();
                },
                'comment.post.user',
            ])
            ->get();

        return $replies->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });
    }

    public function getUserReply($userId)
    {
        $replies = $this->getUserRepliesData($userId);

        return response()->json([
            'replies' => $replies
        ]);
    }

    public function getUserDeletedRepliesData($userId)
    {
        $deletedReplies = Reply::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->withCount(['votes'])
            ->with([
                'comment' => function ($query) {
                    $query->withTrashed();
                },
                'comment.user',
                'comment.post' => function ($query) {
                    $query->withTrashed();
                },
                'comment.post.user',
            ])
            ->get();

        return $deletedReplies->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });
    }

    public function getUserDeletedReplies($userId)
    {
        $deletedReplies = $this->getUserDeletedRepliesData($userId);

        return response()->json([
            'deletedReplies' => $deletedReplies
        ]);
    }

    public function restore($id)
    {
        $reply = Reply::onlyTrashed()
            ->with([
                'comment' => function ($query) {
                    $query->withTrashed();
                },
                'comment.post' => function ($query) {
                    $query->withTrashed();
                }
            ])
            ->findOrFail($id);

        if ($reply->user_id === Auth::id() && $reply->deleted_at && !$reply->comment->deleted_at && !$reply->comment->post->deleted_at) {
            $reply->restore();
            return redirect()->back()->with('success', 'Reply restored successfully');
        } else if ($reply->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Invalid Credentials');
        } else if ($reply->comment->deleted_at) {
            return redirect()->back()->with('error', 'Cannot restore to a deleted comment');
        } else if ($reply->comment->post->deleted_at) {
            return redirect()->back()->with('error', 'Cannot restore to a deleted post');
        } else {
            return redirect()->back()->with('error', 'Reply must be deleted to be restored');
        }
    }
}

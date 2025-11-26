<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Reply;
use App\Models\Comment;
use App\Models\InboxMessage;
use Illuminate\Http\Request;
use App\Models\PinnedHomePost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getLatest(Request $request)
    {
        // Pinned
        $pinned = PinnedHomePost::with('post')
            ->latest()
            ->take(5)
            ->get()
            ->pluck('post')
            ->filter();

        $pinned->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->isPinnedHome = $post->pinnedInHome()->exists() ? 1 : 0;
            return $post;
        });

        // Posts
        $posts = Post::whereNull('deleted_at')
            ->whereNotIn('id', $pinned->pluck('id')->all())
            ->whereHas('group', function ($query) {
                $query->where('is_private', 0)
                    ->where('type', '!=', 'academic');
            })
            ->latest()
            ->with(['group'])
            ->withCount(['votes', 'comments'])
            ->paginate(15);

        $posts->getCollection()->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->isPinnedHome = $post->pinnedInHome()->exists() ? 1 : 0;
            return $post;
        });

        // Right Side Groups
        $user = User::findOrFail(Auth::id());

        $createdGroups = $user->groups()
            ->wherePivot('role', 'owner')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $moderatedGroups = $user->groups()
            ->wherePivot('role', 'moderator')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $joinedGroups = $user->groups()
            ->wherePivot('role', 'member')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        if ($request->ajax()) {
            $html = '';
            foreach ($posts as $post) {
                $html .= view('components.post', compact('post'))->render();
            }
            return response()->json([
                'html' => $html,
                'next_page' => $posts->currentPage() < $posts->lastPage() ? $posts->currentPage() + 1 : NULL,
            ]);
        }

        return view('home', compact(
            'pinned',
            'posts',
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function getUserPost(Request $request, $userId)
    {
        $posts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $userId
        ])->latest()
            ->with(['group' => function ($query) {
                $query->withTrashed();
            }])
            ->withCount(['votes', 'comments'])
            ->paginate(15);

        $posts->getCollection()->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        $html = '';
        foreach ($posts as $post) {
            $html .= view('components.post', compact('post'))->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $posts->currentPage() < $posts->lastPage() ? $posts->currentPage() + 1 : NULL,
        ]);
    }

    public function getUserDeletedPostsData($userId)
    {
        $deletedPosts = Post::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->with(['group' => function ($query) {
                $query->withTrashed();
            }])
            ->withCount(['votes'])
            ->get();

        return $deletedPosts->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->type = 'post';
            return $post;
        });
    }

    public function getUserDeletedPosts(Request $request, $userId)
    {
        $deletedPosts = Post::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->with(['group' => function ($query) {
                $query->withTrashed();
            }])
            ->withCount(['votes', 'comments'])
            ->paginate(15, ['*'], 'page', $request->get('page', 1));

        $deletedPosts->getCollection()->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        $html = '';
        foreach ($deletedPosts as $deletedPost) {
            $html .= view(
                'components.post',
                ['post' => $deletedPost]
            )->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $deletedPosts->currentPage() < $deletedPosts->lastPage() ? $deletedPosts->currentPage() + 1 : NULL,
        ]);
    }

    public function getPost($id)
    {
        $post = Post::where('id', $id)
            ->with(['group', 'pinnedInGroups'])
            ->withCount('comments')
            ->firstOrFail();
        $post->votes = $post->getVoteCountAttribute();
        $post->userVote = $post->getUserVoteAttribute();
        $post->isPinned = $post->pinnedInGroups->contains('id', $post->group->id);
        $post->isPinnedHome = $post->pinnedInHome()->exists() ? 1 : 0;

        $homeGroup = Group::with(['members' => function ($query) {
            $query->wherePivotIn('role', ['owner', 'moderator']);
        }])->find(1);

        $homeAdmin = $homeGroup ? $homeGroup->members : collect();

        $comments = Comment::where('post_id', $id)
            ->withCount(['votes', 'replies'])
            ->get()
            ->map(function ($comment) {
                $comment->votes = $comment->getVoteCountAttribute();
                $comment->userVote = $comment->getUserVoteAttribute();
                $comment->replies = Reply::where('comment_id', $comment->id)
                    ->get();
                return $comment;
            });

        // Right Side Groups
        $user = User::findOrFail(Auth::id());

        $createdGroups = $user->groups()
            ->wherePivot('role', 'owner')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $moderatedGroups = $user->groups()
            ->wherePivot('role', 'moderator')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $joinedGroups = $user->groups()
            ->wherePivot('role', 'member')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('post', compact(
            'post',
            'homeAdmin',
            'comments',
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function create(Request $request, $id)
    {
        $postData = $request->validate([
            'create-post-title' => ['required', 'max:70'],
            'create-post-content' => ['required', 'max:2000'],
        ]);

        $post = Post::create([
            'title' => $postData['create-post-title'],
            'content' => $postData['create-post-content'],
            'user_id' => Auth::id(),
            'group_id' => $id,
        ]);

        if ((int) $id !== 1) {
            $group = Group::find($id);
            $creator = Auth::user();

            if ($group && $creator) {
                $creatorRole = DB::table('group_members')
                    ->where('group_id', $group->id)
                    ->where('user_id', $creator->id)
                    ->value('role');

                $canNotify = $group->owner_id === $creator->id
                    || in_array($creatorRole, ['owner', 'moderator'], true);

                if ($canNotify) {
                    $groupName = e($group->name);
                    $creatorName = e($creator->name);
                    $postTitle = e($post->title);

                    $title = "{$creatorName} posted in <a href='/group/{$group->id}'>{$groupName}</a>";
                    $body = "{$creatorName} shared &ldquo;{$postTitle}&rdquo; in <a href='/group/{$group->id}'>{$groupName}</a>. <a href='/post/{$post->id}'>Read the post</a>.";

                    InboxMessage::notifyGroupMembers(
                        $group,
                        $creator,
                        'group_post_notification',
                        $title,
                        $body,
                        ['roles' => ['member']]
                    );
                }
            }
        }

        if ((int)$id === 1) return redirect()->back()->with('success', 'Post created successfully');
        else return redirect()->back()->with('success', 'Post created successfully');
    }

    public function pinToggleRouter($id, Request $request)
    {
        $post = Post::findOrFail($id);

        return ($post->group_id == 1) ?
            $this->pinHomeToggle($id, $request) :
            $this->pinToggle($id, $request);
    }

    public function pinHomeToggle($id, Request $request)
    {
        $post = Post::findOrFail($id);

        $user = Auth::user();

        $homeGroup = \App\Models\Group::with(['members' => function ($q) {
            $q->wherePivotIn('role', ['owner', 'moderator']);
        }])->find(1);

        $isHomeAdmin = $homeGroup && $homeGroup->members->pluck('id')->contains($user->id);

        if (!$isHomeAdmin) {
            return redirect()->back()->with('error', 'Must be Home owner/moderator to perform action');
        } else {
            $isPinned = PinnedHomePost::where('post_id', $post->id)->exists();

            if ($isPinned) {
                PinnedHomePost::where('post_id', $post->id)->delete();
                $status = 'unpinned from home';
            } else {
                // check if less than 5 posts in home
                if (PinnedHomePost::count() >= 5) {
                    return redirect()->back()->with('error', 'You can only pin up to 5 posts in Home.');
                }

                PinnedHomePost::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
                $status = 'pinned to home';
            }

            return redirect()->back()->with('success', 'Post ' . $status . ' successfully');
        }
    }

    public function pinToggle($id, Request $request)
    {
        $post = Post::with('group.members')
            ->findOrFail($id);

        $user = Auth::user();

        $membership = $post->group->members->where('id', $user->id)->first();

        if (!$membership || !in_array($membership->pivot->role, ['owner', 'moderator'])) {
            return redirect()->back()->with('error', 'Must be group owner/moderator to perform action');
        } else {
            $isPinned = $post->pinnedInGroups->contains('id', $post->group->id);

            if ($isPinned) {
                $post->pinnedInGroups()->detach($post->group->id);
                $status = 'unpinned';
            } else {
                if ($post->group->pinnedPosts()->count() >= 5) {
                    return redirect()->back()->with('error', 'You can only pin up to 5 posts per group.');
                }

                $post->pinnedInGroups()->attach($post->group->id, ['user_id' => $user->id]);
                $status = 'pinned';
            }

            return redirect()->back()->with('success', 'Post ' . $status . ' successfully');
        }
    }

    public function edit($id, Request $request)
    {
        $postData = $request->validate([
            'edit-post-title' => ['required', 'max:70'],
            'edit-post-content' => ['required', 'max:2000'],
        ]);
        $post = Post::findOrFail($id);
        if ($post->user_id === Auth::id()) {
            $post->update([
                'title' => $postData['edit-post-title'],
                'content' => $postData['edit-post-content'],
            ]);
            return redirect()->back()->with('success', 'Post edited successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function delete($id)
    {
        $post = Post::with('group.members')
            ->findOrFail($id);

        $membership = $post->group->members->where('id', Auth::id())->first();

        if ($post->user_id === Auth::id() || ($membership && in_array($membership->pivot->role, ['owner', 'moderator']))) {
            $post->delete();
            return redirect()->back()->with('success', 'Post deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        if ($post->user_id === Auth::id()) {
            $post->restore();
            return redirect()->back()->with('success', 'Post restored successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Attributes\DB;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\CommentController;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function showLanding()
    {
        return view('landing-page');
    }

    public function register(Request $request)
    {
        try {
            $userData = $request->validate([
                'name' => ['required', Rule::unique('users', 'name')],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'confirmed']
            ]);
            $userData['password'] = bcrypt($userData['password']);

            User::create($userData);

            return redirect('/')->with('success', 'Account created successfully');
        } catch (ValidationException $e) {
            return redirect('/')
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function login(Request $req)
    {
        $user = $req->validate([
            'login-email' => ['required'],
            'login-password' => ['required']
        ]);

        if (Auth::attempt(['name' => $user['login-name'], 'password' => $user['login-password']])) {
            $req->session()->regenerate();
            return redirect('/home')->with('success', 'Logged in successfully');
        }

        return redirect('/')->with('error', 'Invalid credentials');
    }

    // Google login handler
    public function googleLogin(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email']
        ]);

        // Try to find user by email
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            // Create user if not exists
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                // You may want to generate a random password or set a default
                'password' => bcrypt(str()->random(16)),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        return redirect('/home')->with('success', 'Logged in with Google');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function loadUser($id)
    {
        $user = User::findOrFail($id);

        // Overview
        $overviewPosts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withcount(['votes', 'comments'])
            ->get();

        $overviewPosts->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->type = 'post';
            return $post;
        });

        $overviewComments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withcount(['votes'])
            ->with([
                'post' => function ($query) {
                    $query->withTrashed();
                },
                'post.user',
                'user',
            ])
            ->get();

        $overviewComments->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });

        $overviewReplies = Reply::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
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

        $overviewReplies->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });

        $perPage = 15;
        $currentPage = request('page', 1);

        $overviewAll = $overviewPosts->concat($overviewComments)->concat($overviewReplies)->sortByDesc('created_at');
        $overviewCount = $overviewAll->count();
        $offset = ($currentPage - 1) * $perPage;
        $overviewItems = $overviewAll->slice($offset, $perPage)->values();

        $postCount = $overviewPosts->count();
        $commentCount = $overviewComments->count();
        $replyCount = $overviewReplies->count();
        $likeCount = $overviewAll->sum('votes');

        $overview = new \Illuminate\Pagination\LengthAwarePaginator(
            $overviewItems,
            $overviewCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        // Posts
        $posts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withCount(['votes', 'comments'])
            ->paginate(15);

        $posts->getCollection()->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        // Comments & Replies
        $comments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withCount(['votes'])
            ->with([
                'post' => function ($query) {
                    $query->withTrashed();
                },
                'post.user',
                'user',
            ])
            ->paginate(15);

        $comments->getCollection()->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->replyCount = $comment->getReplyCountAttribute();
            $comment->type = 'comment';
            return $comment;
        });

        $replies = Reply::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
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
                'comment.post.user'
            ])
            ->paginate(15);

        $replies->getCollection()->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });

        $repliesCount = $replies->count();

        $commentsPerPage = 15;
        $commentsCurrentPage = request('page', 1);

        $commentsAndReplies = $comments->concat($replies)->sortByDesc('created_at');
        $commentsAndRepliesCount = $commentsAndReplies->count();
        $commentsOffset = ($commentsCurrentPage - 1) * $commentsPerPage;
        $commentsItems = $commentsAndReplies->slice($commentsOffset, $commentsPerPage)->values();

        $comments = new \Illuminate\Pagination\LengthAwarePaginator(
            $commentsItems,
            $commentsAndRepliesCount,
            $commentsPerPage,
            $commentsCurrentPage,
            ['path' => request()->url()]
        );

        // Deleted Overview
        $deletedOverview = $this->getUserDeletedOverview($user->id);
        // Deleted Posts
        $deletedPosts = Post::onlyTrashed()
            ->where(['user_id' => $user->id])
            ->latest()
            ->withCount(['votes', 'comments'])
            ->paginate(15);

        $deletedPosts->getCollection()->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        // Deleted Comments
        $deletedComments = Comment::onlyTrashed()
            ->where(['user_id' => $user->id])
            ->latest()
            ->withCount(['votes'])
            ->with(['post' => function ($query) {
                $query->withTrashed();
            }, 'post.user'])
            ->paginate(15);

        $deletedComments->getCollection()->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });

        $deletedReplies = Reply::onlyTrashed()
            ->where(['user_id' => $user->id])
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
            ->paginate(15);

        $deletedReplies->getCollection()->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });

        $deletedCommentsPerPage = 15;
        $deletedCommentsCurrentPage = request('page', 1);

        $deletedCommentsAndReplies = $deletedComments->concat($deletedReplies)->sortByDesc('created_at');
        $deletedCommentsAndRepliesCount = $deletedCommentsAndReplies->count();
        $deletedCommentsOffset = ($commentsCurrentPage - 1) * $commentsPerPage;
        $deletedCommentsItems = $deletedCommentsAndReplies->slice($deletedCommentsOffset, $deletedCommentsPerPage)->values();

        $deletedCommentsAndReplies = new \Illuminate\Pagination\LengthAwarePaginator(
            $deletedCommentsItems,
            $deletedCommentsAndRepliesCount,
            $deletedCommentsPerPage,
            $deletedCommentsCurrentPage,
            ['path' => request()->url()]
        );

        // Response
        if ($user->id == Auth::id()) {
            return view('profile', compact(
                'user',
                'postCount',
                'commentCount',
                'replyCount',
                'likeCount',
                'overview',
                'posts',
                'comments',
                'deletedOverview',
                'deletedPosts',
                'deletedCommentsAndReplies',
            ));
        } else {
            return view('user', compact(
                'user',
                'postCount',
                'commentCount',
                'replyCount',
                'likeCount',
                'overview',
                'posts',
                'comments',
            ));
        }
    }

    public function loadSettings($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return view('user-settings');
        } else {
            return $this->loadUser($id);
        }
    }

    public function getUserOverview($id)
    {
        $user = User::findOrFail($id);
        $perPage = 15;
        $currentPage = request('page', 1);

        $overviewPosts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withcount([
                'votes',
                'comments'
            ])
            ->get();

        $overviewPosts->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->type = 'post';
            return $post;
        });

        $overviewComments = Comment::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withcount(['votes'])
            ->with([
                'post' => function ($query) {
                    $query->withTrashed();
                },
                'post.user',
                'user',
            ])
            ->get();

        $overviewComments->transform(function ($comment) {
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });

        $overviewReplies = Reply::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
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

        $overviewReplies->transform(function ($reply) {
            $reply->votes = $reply->getVoteCountAttribute();
            $reply->userVote = $reply->getUserVoteAttribute();
            $reply->type = 'reply';
            return $reply;
        });

        $overviewAll = $overviewPosts->concat($overviewComments)->concat($overviewReplies)->sortByDesc('created_at');
        $overviewCount = $overviewAll->count();
        $offset = ($currentPage - 1) * $perPage;
        $overviewItems = $overviewAll->slice($offset, $perPage)->values();

        $overview = new \Illuminate\Pagination\LengthAwarePaginator(
            $overviewItems,
            $overviewCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        if (request()->ajax()) {
            $html = '';
            foreach ($overview as $item) {
                if ($item->type === 'post') {
                    $html .= view('components.post', ['post' => $item])->render();
                } else if ($item->type === 'comment') {
                    $html .= view('components.profile-comment', ['comment' => $item])->render();
                } else {
                    $html .= view('components.profile-reply', ['reply' => $item])->render();
                }
            }

            return response()->json([
                'html' => $html,
                'next_page' => $overview->hasMorePages() ? $overview->currentPage() + 1 : NULL
            ]);
        }

        return view('profile', compact('user', 'overview'));
    }

    public function getUserCommentsAndReplies($id)
    {
        $perPage = 15;
        $currentPage = request('page', 1);

        $commentController = new CommentController();
        $comments = $commentController->getUserCommentsData($id);

        $replyController = new ReplyController();
        $replies = $replyController->getUserRepliesData($id);

        $combined = $comments->concat($replies)->sortByDesc('created_at');
        $combinedCount = $combined->count();

        $offset = ($currentPage - 1) * $perPage;
        $combinedItems = $combined->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $combinedItems,
            $combinedCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        if (request()->ajax()) {
            $html = '';
            foreach ($combinedItems as $item) {
                if ($item->type === 'comment') {
                    $html .= view('components.profile-comment', ['comment' => $item])->render();
                } elseif ($item->type === 'reply') {
                    $html .= view('components.profile-reply', ['reply' => $item])->render();
                }
            }

            return response()->json([
                'html' => $html,
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : NULL,
            ]);
        }

        return response()->json(['error' => 'Invalid request']);
    }

    public function getUserDeletedOverview($id)
    {
        $user = User::findOrFail($id);
        $perPage = 15;
        $currentPage = request('page', 1);

        $postController = new PostController();
        $deletedPosts = $postController->getUserDeletedPostsData($user->id);

        $commentController = new CommentController();
        $deletedComments = $commentController->getUserDeletedCommentsData($user->id);

        $replyController = new ReplyController();
        $deletedReplies = $replyController->getUserDeletedRepliesData($user->id);

        $combined = $deletedPosts->concat($deletedComments)->concat($deletedReplies)->sortByDesc('created_at');
        $combinedCount = $combined->count();
        $offset = ($currentPage - 1) * $perPage;
        $combinedItems = $combined->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $combinedItems,
            $combinedCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        if (request()->ajax()) {
            $html = '';
            foreach ($combinedItems as $index => $item) {
                if ($item->type === 'post') {
                    $html .= view('components.post', ['post' => $item])->render();
                } elseif ($item->type === 'comment') {
                    $html .= view('components.profile-comment', ['comment' => $item])->render();
                } elseif ($item->type === 'reply') {
                    $html .= view('components.profile-reply', ['reply' => $item])->render();
                }
            }

            return response()->json([
                'html' => $html,
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : NULL,
            ]);
        } else {
            return $paginator;
        }
    }

    public function getUserDeletedCommentsAndReplies($id)
    {
        // try {
        $user = User::findOrFail($id);
        $perPage = 15;
        $currentPage = request('page', 1);

        // Log::info("Getting deleted content for user {$id}, page {$currentPage}");

        $commentController = new CommentController();
        $deletedComments = $commentController->getUserDeletedCommentsData($user->id);
        // Log::info("Got " . $deletedComments->count() . " deleted comments");

        $replyController = new ReplyController();
        $deletedReplies = $replyController->getUserDeletedRepliesData($user->id);
        // Log::info("Got " . $deletedReplies->count() . " deleted replies");

        $combined = $deletedComments->concat($deletedReplies)->sortByDesc('created_at');
        $combinedCount = $combined->count();
        // Log::info("Combined count: {$combinedCount}");

        $offset = ($currentPage - 1) * $perPage;
        $combinedItems = $combined->slice($offset, $perPage)->values();
        // Log::info("Items for page {$currentPage}: " . $combinedItems->count());

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $combinedItems,
            $combinedCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        if (request()->ajax()) {
            $html = '';
            foreach ($combinedItems as $index => $item) {
                try {
                    // Log::info("Rendering item {$index}, type: {$item->type}, id: {$item->id}");

                    if ($item->type === 'comment') {
                        $html .= view('components.profile-comment', ['comment' => $item])->render();
                    } elseif ($item->type === 'reply') {
                        $html .= view('components.profile-reply', ['reply' => $item])->render();
                    }
                } catch (\Exception $e) {
                    // Log::error("Error rendering item {$index} (type: {$item->type}, id: {$item->id}): " . $e->getMessage());
                    // Skip this item and continue
                    continue;
                }
            }

            return response()->json([
                'html' => $html,
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : NULL,
            ]);
        }

        return response()->json(['error' => 'Invalid request']);
        // } catch (\Exception $e) {
        //     Log::error("Error in getUserDeletedCommentsAndReplies: " . $e->getMessage());
        //     Log::error("Stack trace: " . $e->getTraceAsString());
        //     return response()->json(['error' => 'Internal server error: ' . $e->getMessage()], 500);
        // }
    }
}

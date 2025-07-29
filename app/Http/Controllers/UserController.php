<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function showLogin(){
        return view('login');
    }

    public function register(Request $request){
        try {
            $userData = $request->validate([
                'name' => ['required', Rule::unique('users', 'name')],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'confirmed']
            ]);
            $userData['password'] = bcrypt($userData['password']);

            User::create($userData);

            return redirect('/')->with('success', 'Account created successfully');
        } catch (ValidationException $e){
            return redirect('/')
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function login(Request $req){
        $user = $req->validate([
            'login-name' => ['required'],
            'login-password' => ['required']
        ]);

        if(Auth::attempt(['name' => $user['login-name'], 'password' => $user['login-password']])){
            $req->session()->regenerate();
            return redirect('/home')->with('success', 'Logged in successfully');
        }

        return redirect('/')->with('error', 'Invalid credentials');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function loadUser($id){
        $user = User::findOrFail($id);

        // Overview
            $perPage = 15;
            $currentPage = request('page', 1);

            $overviewPosts = Post::where([
                'deleted_at' => NULL,
                'user_id' => $user->id
            ])->latest()
                ->withcount(['votes', 'comments'])
                ->get();

            $overviewPosts->transform(function($post){
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
                ->with(['post', 'post.user'])
                ->get();

            $overviewComments->transform(function($comment){
                $comment->votes = $comment->getVoteCountAttribute();
                $comment->userVote = $comment->getUserVoteAttribute();
                $comment->type = 'comment';
                return $comment;
            });
            
            $overviewAll = $overviewPosts->concat($overviewComments)->sortByDesc('created_at');
            $overviewCount = $overviewAll->count();
            $offset = ($currentPage - 1) * $perPage;
            $overviewItems = $overviewAll->slice($offset, $perPage)->values();

            $postCount = $overviewPosts->count();
            $commentCount = $overviewComments->count();
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
            
            $posts->getCollection()->transform(function($post) {
                $post->votes = $post->getVoteCountAttribute();
                $post->userVote = $post->getUserVoteAttribute();
                return $post;
            });

        // Comments
            $comments = Comment::where([
                'deleted_at' => NULL,
                'user_id' => $user->id
            ])->latest()
                ->withCount(['votes'])
                ->with(['post', 'post.user'])
                ->paginate(15);

            $comments->getCollection()->transform(function($comment) {
                $comment->votes = $comment->getVoteCountAttribute();
                $comment->userVote = $comment->getUserVoteAttribute();
                return $comment;
            });

        // Response
            if($user->id == Auth::id()){
                return view('profile', compact(
                    'user',
                    'postCount',
                    'commentCount',
                    'likeCount',
                    'overview',
                    'posts',
                    'comments',
                ));
            }
            else{
                return view('user', compact(
                    'user', 
                    'overview',
                    'posts',
                    'comments',
                ));
            }
    }

    public function getUserOverview($id){
        $user = User::findOrFail($id);
        $perPage = 15;
        $currentPage = request('page', 1);

        $overviewPosts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $user->id
        ])->latest()
            ->withcount(['votes', 'comments'])
            ->get();

        $overviewPosts->transform(function($post){
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
            ->with(['post', 'post.user'])
            ->get();

        $overviewComments->transform(function($comment){
            $comment->votes = $comment->getVoteCountAttribute();
            $comment->userVote = $comment->getUserVoteAttribute();
            $comment->type = 'comment';
            return $comment;
        });

        $overviewAll = $overviewPosts->concat($overviewComments)->sortByDesc('created_at');
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

        if(request()->ajax()){
            $html = '';
            foreach($overview as $item){
                if($item->type === 'post'){
                    $html .= view('components.post', ['post' => $item])->render();
                } else {
                    $html .= view('components.profile-comment', ['comment' => $item])->render();
                }
            }

            return response()->json([
                'html' => $html,
                'next_page' => $overview->hasMorePages() ? $overview->currentPage()+1 : NULL
            ]);
        }

        return view('profile', compact('user', 'overview'));
    }
}

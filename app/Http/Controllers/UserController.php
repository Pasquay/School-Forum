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

            $overview = $overviewPosts->concat($overviewComments)->sortByDesc('created_at');
            
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
}

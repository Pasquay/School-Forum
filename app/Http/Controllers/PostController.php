<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function getLatest(Request $request){
        $posts = Post::whereNull('deleted_at')
            ->latest()
            ->withCount(['votes', 'comments'])
            ->paginate(15);
            
        $posts->getCollection()->transform(function($post){
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        if($request->ajax()){
            $html = '';
            foreach($posts as $post){
                $html .= view('components.post', compact('post'))->render();
            }
            return response()->json([
                'html' => $html,
                'next_page' => $posts->currentPage() < $posts->lastPage() ? $posts->currentPage()+1 : NULL,
            ]);
        }

        return view('home', ['posts' => $posts]);
    }

    public function getUserPost(Request $request, $userId){
        $posts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $userId 
        ])->latest()
            ->withCount(['votes', 'comments'])
            ->paginate(15);
     
        $posts->getCollection()->transform(function($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        $html = '';
        foreach($posts as $post){
            $html .= view('components.post', compact('post'))->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $posts->currentPage() < $posts->lastPage() ? $posts->currentPage()+1 : NULL,
        ]);
    }

    public function getPost($id){
        $post = Post::where('id', $id)
            ->withCount('comments')
            ->firstOrFail();
        $post->votes = $post->getVoteCountAttribute();
        $post->userVote = $post->getUserVoteAttribute();

        $comments = Comment::where('post_id', $id)
            ->withCount(['votes', 'replies'])
            ->get()
            ->map(function($comment){
                $comment->votes = $comment->getVoteCountAttribute();
                $comment->userVote = $comment->getUserVoteAttribute();
                $comment->replies = Reply::where('comment_id', $comment->id)
                    ->get();
                return $comment;
            });
        return view('post', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function create(Request $request){
        $postData = $request->validate([
            'create-post-title' => ['required', 'max:70'],
            'create-post-content' => ['required', 'max:2000'],
        ]);

        Post::create([
            'title' => $postData['create-post-title'],
            'content' => $postData['create-post-content'],
            'user_id' => Auth::id()
        ]);

        return redirect('/home')->with('success', 'Post created successfully');
    }

    public function edit($id, Request $request){
        $postData = $request->validate([
            'edit-post-title' => ['required', 'max:70'],
            'edit-post-content' => ['required', 'max:2000'],
        ]);
        $post = Post::findOrFail($id);
        if ($post->user_id == Auth::id()){
            $post->update([
                'title' => $postData['edit-post-title'],
                'content' => $postData['edit-post-content'],
            ]);
            return redirect('/post/' . $id)->with('success', 'Post edited successfully');
        } else {
            return redirect('/post/' . $id)->with('error', 'Invalid credentials');
        }
    }

    public function delete($id){
        $post = Post::findOrFail($id);
        if($post->user_id == Auth::id()){
            $post->delete();
            return redirect('/home')->with('success', 'Post deleted successfully');    
        } else {
            return redirect('/home')->with('error', 'Invalid credentials');
        }
    }
}

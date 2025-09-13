<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function getLatest(Request $request){
        // Posts
            $posts = Post::whereNull('deleted_at')
                ->latest()
                ->with(['group'])
                ->withCount(['votes', 'comments'])
                ->paginate(15);
                
            $posts->getCollection()->transform(function($post){
                $post->votes = $post->getVoteCountAttribute();
                $post->userVote = $post->getUserVoteAttribute();
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

        return view('home', compact(
            //'pinned',
            'posts',
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function getUserPost(Request $request, $userId){
        $posts = Post::where([
            'deleted_at' => NULL,
            'user_id' => $userId 
        ])->latest()
            ->with(['group' => function($query){
                $query->withTrashed();
            }])
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

    public function getUserDeletedPostsData($userId){
        $deletedPosts = Post::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->with(['group' => function($query){
                $query->withTrashed();
            }])
            ->withCount(['votes'])
            ->get();

        return $deletedPosts->transform(function($post){
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->type = 'post';
            return $post;
        });
    }

    public function getUserDeletedPosts(Request $request, $userId){
        $deletedPosts = Post::onlyTrashed()
            ->where(['user_id' => $userId])
            ->latest()
            ->with(['group' => function($query){
                $query->withTrashed();
            }])
            ->withCount(['votes', 'comments'])
            ->paginate(15, ['*'], 'page', $request->get('page', 1));

        $deletedPosts->getCollection()->transform(function($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        $html = '';
        foreach($deletedPosts as $deletedPost){
            $html .= view('components.post', 
                ['post' => $deletedPost]
            )->render();
        }
        return response()->json([
            'html' => $html,
            'next_page' => $deletedPosts->currentPage() < $deletedPosts->lastPage() ? $deletedPosts->currentPage()+1 : NULL,
        ]);
    }

    public function getPost($id){
        $post = Post::where('id', $id)
            ->with(['group', 'pinnedInGroups'])
            ->withCount('comments')
            ->firstOrFail();
        $post->votes = $post->getVoteCountAttribute();
        $post->userVote = $post->getUserVoteAttribute();
        $post->isPinned = $post->pinnedInGroups->contains('id', $post->group->id);

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

    public function create(Request $request, $id){
        $postData = $request->validate([
            'create-post-title' => ['required', 'max:70'],
            'create-post-content' => ['required', 'max:2000'],
        ]);

        Post::create([
            'title' => $postData['create-post-title'],
            'content' => $postData['create-post-content'],
            'user_id' => Auth::id(),
            'group_id' => $id,
        ]);

        if((int)$id === 1) return redirect('/home')->with('success', 'Post created successfully');
        else return redirect('/group/' . $id)->with('success', 'Post created successfully');
    }

    public function pinHomeToggle($id, Request $request){

    }

    public function pinToggle($id, Request $request){
        $post = Post::with('group.members')
                    ->findOrFail($id);
        
        $user = Auth::user();

        $membership = $post->group->members->where('id', $user->id)->first();
        
        if(!$membership || !in_array($membership->pivot->role, ['owner', 'moderator'])){
            return redirect('/post/' . $id)->with('error', 'Must be group owner/moderator to perform action');
        } else {
            $isPinned = $post->pinnedInGroups->contains('id', $post->group->id);

            if($isPinned){
                $post->pinnedInGroups()->detach($post->group->id);
                $status = 'unpinned';
            } else {
                if($post->group->pinnedPosts()->count() >= 5){
                    return redirect('/post/' . $id)->with('error', 'You can only pin up to 5 posts per group.');
                }

                $post->pinnedInGroups()->attach($post->group->id, ['user_id' => $user->id]);
                $status = 'pinned';
            }
            
            return redirect('/post/' . $id)->with('success', 'Post ' . $status . ' successfully');
        }
    }

    public function edit($id, Request $request){
        $postData = $request->validate([
            'edit-post-title' => ['required', 'max:70'],
            'edit-post-content' => ['required', 'max:2000'],
        ]);
        $post = Post::findOrFail($id);
        if ($post->user_id === Auth::id()){
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
        $post = Post::with('group.members')
                    ->findOrFail($id);

        $membership = $post->group->members->where('id', Auth::id())->first();

        if($post->user_id === Auth::id() || ($membership && in_array($membership->pivot->role, ['owner', 'moderator']))){
            $post->delete();
            return redirect('/home')->with('success', 'Post deleted successfully');    
        } else {
            return redirect('/home')->with('error', 'Invalid credentials');
        }
    }

    public function restore($id){
        $post = Post::onlyTrashed()->findOrFail($id);
        if ($post->user_id === Auth::id() && $post->deleted_at){
            $post->restore();
            return redirect('/post/' . $id)->with('success', 'Post restored successfully');
        } else if ($post->user_id != Auth::id()){
            return redirect('/user/' . Auth::id())->with('error', 'Invalid Credentials');
        } else {
            return redirect('/user/' . Auth::id())->with('error', 'Post must be deleted to be restored');
        }
    }
}

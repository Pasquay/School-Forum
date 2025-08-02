<?php

namespace App\Models;

use App\Models\User;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    // USERS
    public function user(){
        return $this->belongsTo(User::class);
    }

    // POSTS
    public function post(){
        return $this->belongsTo(Post::class);
    }

    // VOTES
    public function votes(){
        return $this->hasMany(CommentVote::class);
    }

    public function getVoteCountAttribute(){
        $upvotes = $this->votes()->where('vote', 1)->count();
        $downvotes = $this->votes()->where('vote', -1)->count();
        return $upvotes - $downvotes; 
    }
    
    public function getUserVoteAttribute(){
        return $this->votes()->where([
            'comment_id' => $this->id,
            'user_id' => Auth::id()
        ])->value('vote');
    }

    // REPLIES
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function getReplyCountAttribute(){
        return $this->replies()->whereNull('deleted_at')->count();
    }
}

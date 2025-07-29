<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'comment_id',
        'user_id',
        'content'
    ];

    // USERS 
    public function user(){
        return $this->belongsTo(User::class);
    }

    // COMMENTS
    public function comment(){
        return $this->belongsTo(Comment::class);
    }

    // VOTES
    public function votes(){
        return $this->hasMany(ReplyVote::class);
    }

    public function getVoteCountAttribute(){
        $upvotes = $this->votes()->where('vote', 1)->count();
        $downvotes = $this->votes()->where('vote', -1)->count();
        return $upvotes - $downvotes;
    }
    
    public function getUserVoteAttribute(){
        return ReplyVote::where([
            'reply_id' => $this->id,
            'user_id' => Auth::id()
        ])->value('vote');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplyVote extends Model
{
    protected $fillable = [
        'reply_id',
        'user_id',
        'vote'
            // 1 = upvote
            // 0 = no vote
            // -1 = downvote
    ];

    public function reply(){
        return $this->belongsTo(Reply::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

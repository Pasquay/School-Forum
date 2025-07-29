<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    public function scopeLatest($query){
        return $query->orderBy('created_at', 'desc');
    }
    
    // USERS
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    // VOTES
    public function votes(){
        return $this->hasMany(Vote::class);
    }

    public function getVoteCountAttribute(){
        $upvotes = $this->votes()->where('vote', 1)->count();
        $downvotes = $this->votes()->where('vote', -1)->count();
        return $upvotes - $downvotes;
    }

    public function getUserVoteAttribute(){
        return Vote::where([
            'post_id' => $this->id,
            'user_id' => Auth::id()
        ])->value('vote');
    }

    // COMMENTS
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}

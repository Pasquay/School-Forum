<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'group_id'
    ];
    
    /**
     * Relations
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function pinnedInGroups()
    {
        return $this->belongsToMany(Group::class, 'pinned_post')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }

    /**
     * Helper Functions
     */

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
    
    public function scopeLatest($query){
        return $query->orderBy('created_at', 'desc');
    }
}

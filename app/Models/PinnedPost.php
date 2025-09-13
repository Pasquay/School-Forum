<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinnedPost extends Model
{
    protected $fillable = [
        'group_id',
        'post_id',
        'user_id',
    ];

    /**
     * RELATIONS
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

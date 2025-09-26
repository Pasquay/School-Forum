<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'banner',
        'rules',
        'resources',
        'owner_id',
        'member_count',
        'is_private',
        'type',
        'members_can_post',
        'members_can_comment',
        'members_can_invite',
        'auto_approve_posts'
    ];

    protected $casts = [
        'rules' => 'array',
        'resources' => 'array',
        'is_private' => 'boolean',
        'members_can_post' => 'boolean',
        'members_can_comment' => 'boolean',
        'members_can_invite' => 'boolean',
        'auto_approve_posts' => 'boolean',
    ];

    /**
     * Relations
     */

    public function owner()
    {
        return $this->belongsTo(User::class);
    } 

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot(['role', 'is_starred', 'is_muted'])
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function pinnedPosts()
    {
        return $this->belongsToMany(Post::class, 'pinned_post')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }

    /**
     * User Helper Functions
     */

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isModerator(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'moderator')
            ->exists();
    }

    public function getModeratorAndOwnerIds(): array
    {
        $moderatorIds = $this->members()
                             ->wherePivot('role', 'moderator')
                             ->pluck('users.id')
                             ->toArray();

        $ids = $moderatorIds;
        if($this->owner_id) $ids[] = $this->owner_id;

        return array_unique($ids);
    }

    public function getModeratorIds(): array
    {
        $moderatorIds = $this->members()
                             ->wherePivot('role', 'moderator')
                             ->pluck('users.id')
                             ->toArray();

        return array_unique($moderatorIds);
    }

    public function getMemberIds(): array
    {
        $memberIds = $this->members()
                          ->wherePivot('role', 'member')
                          ->pluck('users.id')
                          ->toArray();
                        
        return array_unique($memberIds);
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function getBannerUrl(): ?string
    {
        return $this->banner ? asset('storage/' . $this->banner) : null;
    }

    public function getMemberCount(): ?int
    {
        return $this->members()->count();
    }
}

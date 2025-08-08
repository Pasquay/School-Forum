<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function rules()
    {
        return [
            'bio' => 'nullable|max:500',
        ];
    }

    /**
     * Relations
     */

    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot(['role', 'is_starred', 'is_muted'])
            ->withTimestamps();
    }

    public function starredGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot(['role', 'is_starred', 'is_muted'])
            ->wherePivot('is_starred', true)
            ->withTimestamps();
    }

    public function mutedGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot(['role', 'is_starred', 'is_muted'])
            ->wherePivot('is_muted', true)
            ->withTimestamps();
    }

    /**
     * Group Helper Functions
     */

    public function isMemberOf(Group $group): bool
    {
        return $this->groups()->where('group_id', $group->id)->exists();
    }

    public function isOwnerOf(Group $group): bool
    {
        return $group->owner_id === $this->id;
    }

    public function isModeratorOf(Group $group): bool
    {
        return $this->groups()
                    ->where('group_id', $group->id)
                    ->wherePivot('role', 'moderator')
                    ->exists();
    }

    public function hasStarred(Group $group): bool
    {
        return $this->groups()
                    ->where('group_id', $group->id)
                    ->wherePivot('is_starred', true)
                    ->exists();
    }

    public function hasMuted(Group $group): bool
    {
        return $this->groups()
                    ->where('group_id', $group->id)
                    ->wherePivot('is_muted', true)
                    ->exists();
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Log;
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
        'social_links',
        'photo',
        'role',
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
            'social_links' => 'array',
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

    public function createdAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
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

    /**
     * ON CREATION BEHAVIOR
     */

    protected static function booted()
    {
        static::created(function($user){
            switch($user->role){
                case 'student':
                    $groupIds = [1, 2, 4];
                    break;
                case 'staff':
                    $groupIds = [1, 2, 3];
                    break;
                default:
                    $groupIds = [1, 2];
                    break;
            }

            $defaultGroups = Group::whereIn('id', $groupIds)->get();
            foreach($defaultGroups as $group){
                $group->members()->syncWithoutDetaching([$user->id]);
                // \Log::info('Group: ', $group->name);
            }
            // Log::info('User role on creation: ' . $user->role);
        });
    }
}

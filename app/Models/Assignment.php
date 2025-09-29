<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
        'created_by',
        'assignment_name',
        'description',
        'assignment_type',
        'max_points',
        'date_assigned',
        'date_due',
        'close_date',
        'visibility',
        'submission_type'
    ];

    protected $casts = [
        'date_assigned' => 'datetime',
        'date_due' => 'datetime',
        'close_date' => 'datetime',
        'assignment_type' => 'string',
        'visibility' => 'string',
        'submission_type' => 'string'
    ];

    // Relationships
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('visibility', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('visibility', 'draft');
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->date_due < now() && $this->visibility === 'published';
    }

    public function getIsClosedAttribute()
    {
        return $this->close_date && $this->close_date < now();
    }
}
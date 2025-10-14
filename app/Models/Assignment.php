<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'created_by',
        'assignment_name',
        'description',
        'assignment_type',
        'is_group_assignment',
        'group_size_min',
        'group_size_max',
        'time_limit',
        'max_points',
        'weight',
        'date_assigned',
        'date_due',
        'close_date',
        'visibility',
        'submission_type',
        'allow_late_submissions',
        'late_penalty_percentage',
        'allow_resubmission',
        'max_resubmissions',
        'allow_resubmissions',
        'max_attempts'
    ];

    protected $casts = [
        'date_assigned' => 'datetime',
        'date_due' => 'datetime',
        'close_date' => 'datetime',
        'assignment_type' => 'string',
        'visibility' => 'string',
        'submission_type' => 'string',
        'allow_late_submissions' => 'boolean',
        'allow_resubmission' => 'boolean',
        'allow_resubmissions' => 'boolean',
        'is_group_assignment' => 'boolean',
        'max_attempts' => 'integer'
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

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attachments()
    {
        return $this->hasMany(AssignmentAttachment::class);
    }

    public function rubrics()
    {
        return $this->hasMany(Rubric::class)->orderBy('order');
    }

    // Helper method to check if assignment uses rubrics
    public function hasRubrics()
    {
        return $this->rubrics()->count() > 0;
    }

    // Get total possible points from rubrics
    public function getRubricTotalPoints()
    {
        return $this->rubrics()->sum('max_points');
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
        // Compare in Pacific time to align with UI input/display
        try {
            if (!$this->date_due) {
                return false;
            }
            $nowPt = \Carbon\Carbon::now('America/Los_Angeles');
            $duePt = $this->date_due->copy()->setTimezone('America/Los_Angeles');
            return $duePt->lt($nowPt) && $this->visibility === 'published';
        } catch (\Throwable $e) {
            return $this->date_due < now() && $this->visibility === 'published';
        }
    }

    public function getIsClosedAttribute()
    {
        return $this->close_date && $this->close_date < now();
    }

    /**
     * Determine if the assignment is past due based on Pacific time.
     * Falls back to UTC comparison if anything goes wrong.
     */
    public function isPastDuePacific(): bool
    {
        try {
            if (!$this->date_due) {
                return false;
            }
            $nowPt = \Carbon\Carbon::now('America/Los_Angeles');
            $duePt = $this->date_due->copy()->setTimezone('America/Los_Angeles');
            return $nowPt->gt($duePt);
        } catch (\Throwable $e) {
            return now()->gt($this->date_due);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_text',
        'file_path',
        'external_link',
        'status',
        'is_late',
        'late_penalty_applied',
        'attempt_number',
        'date_submitted',
        'grade',
        'teacher_feedback',
        'graded_at',
        'quiz_started',
        'time_remaining'
    ];

    protected $casts = [
        'date_submitted' => 'datetime',
        'graded_at' => 'datetime',
        'quiz_started' => 'datetime',
        'grade' => 'decimal:2',
        'is_late' => 'boolean',
        'attempt_number' => 'integer',
        'time_remaining' => 'integer'
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quizResponses()
    {
        return $this->hasMany(StudentQuizResponse::class, 'submission_id');
    }

    public function comments()
    {
        return $this->hasMany(SubmissionComment::class, 'submission_id')->orderBy('created_at');
    }

    public function rubricScores()
    {
        return $this->hasMany(RubricScore::class, 'submission_id');
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupAssignmentMember::class, 'submission_id');
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    // Accessors & Mutators
    public function getIsLateAttribute()
    {
        // If the model has a persisted value for is_late, prefer that (set at submit time)
        if (array_key_exists('is_late', $this->attributes) && $this->attributes['is_late'] !== null) {
            return (bool) $this->attributes['is_late'];
        }

        // Otherwise, compute dynamically using Pacific time to match server-side gating
        try {
            if (!$this->date_submitted || !$this->assignment || !$this->assignment->date_due) {
                return false;
            }

            $submittedPt = $this->date_submitted->copy()->setTimezone('America/Los_Angeles');
            $duePt = $this->assignment->date_due->copy()->setTimezone('America/Los_Angeles');
            return $submittedPt->gt($duePt);
        } catch (\Throwable $e) {
            // Fallback to raw comparison if anything goes wrong
            return $this->date_submitted && $this->assignment && $this->assignment->date_due
                ? $this->date_submitted > $this->assignment->date_due
                : false;
        }
    }

    public function getIsGradedAttribute()
    {
        return $this->status === 'graded' && !is_null($this->grade);
    }
}

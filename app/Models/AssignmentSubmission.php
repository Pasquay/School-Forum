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
        'status',
        'date_submitted',
        'grade',
        'teacher_feedback',
        'graded_at'
    ];

    protected $casts = [
        'date_submitted' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2'
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
        return $this->date_submitted && $this->assignment->date_due && 
               $this->date_submitted > $this->assignment->date_due;
    }

    public function getIsGradedAttribute()
    {
        return $this->status === 'graded' && !is_null($this->grade);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RubricScore extends Model
{
    protected $fillable = [
        'rubric_id',
        'submission_id',
        'points_earned',
        'feedback'
    ];

    protected $casts = [
        'points_earned' => 'decimal:2'
    ];

    // Relationships
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }
}

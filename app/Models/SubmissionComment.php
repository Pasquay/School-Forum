<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionComment extends Model
{
    protected $fillable = [
        'submission_id',
        'user_id',
        'comment_text',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    // Relationships
    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

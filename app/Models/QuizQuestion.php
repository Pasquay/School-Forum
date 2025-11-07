<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'assignment_id',
        'question_text',
        'question_type',
        'points',
        'order',
        'correct_answer'
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizQuestionOption::class, 'question_id')->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StudentQuizResponse::class, 'question_id');
    }
}

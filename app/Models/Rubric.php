<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    protected $fillable = [
        'assignment_id',
        'criteria_name',
        'description',
        'max_points',
        'order'
    ];

    protected $casts = [
        'max_points' => 'decimal:2',
        'order' => 'integer'
    ];

    // Relationships
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function scores()
    {
        return $this->hasMany(RubricScore::class);
    }
}

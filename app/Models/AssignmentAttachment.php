<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentAttachment extends Model
{
    protected $fillable = [
        'assignment_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}

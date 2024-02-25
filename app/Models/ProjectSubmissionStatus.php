<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSubmissionStatus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'project_submission_id',
        'user_id',
        'type',
        'status',
        'feedback',
    ];
    
    
    public function projectSubmission() : BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class,'project_submission_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

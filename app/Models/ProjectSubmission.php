<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProjectSubmission extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'title',
        'abstract',
        'categories',
        'subject',
        'professor_id',
        'proofreader_id',
        'attachments',
        'attachments_names',
        'team_id',
        'academic_year',
        'term',
        'status',
    ];
    protected $casts = [
        'attachments' => 'array',
        'attachments_names' => 'array',
    ];
    
    public function statuses() : HasMany
    {
        return $this->hasMany(ProjectSubmissionStatus::class, 'project_submission_id');
    }

    public function team() : BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function teamMembers() : HasManyThrough
    {
        return $this->hasManyThrough(User::class, Team::class, 'id', 'id', 'team_id', 'members');
    }

    public function professor() : BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function proofreader()
    {
        return $this->belongsTo(User::class, 'proofreader_id');
    }

    public function proofreadingRequest() : HasMany
    {
        return $this->hasMany(ProofreadingRequest::class, 'project_submission_id');
    }
    public function proofreadingRequestStatus() : HasOneThrough
    {
        return $this->hasOneThrough(ProofreadingRequestStatus::class, ProofreadingRequest::class, 'project_submission_id','proofreading_request_id',  'id','id');
    }


}

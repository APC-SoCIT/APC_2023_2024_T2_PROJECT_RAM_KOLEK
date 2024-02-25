<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProofreadingRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_submission_id',
        'owner',
        'phone_number',
        'endorser',
        'executive_director',
        'number_pages',
        'number_words',
        'received_date',
        'released_date',
        'proofreader',
        'attachments',
        'attachments_names',
        'status'
    ];
    protected $casts = [
        'attachments' => 'array',
        'attachments_names' => 'array',
    ];

    public function projectSubmission() : BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class, 'project_submission_id');
    }
    public function statuses() : HasMany
    {
        return $this->hasMany(ProofreadingRequestStatus::class, 'proofreading_request_id');
    }
    public function latestStatus() :HasOne
    {
        return $this->hasOne(ProofreadingRequestStatus::class, 'proofreading_request_id')->latest();
    }
    public function team() : HasOneThrough
    {
        return $this->hasOneThrough(Team::class, ProjectSubmission::class, 'team_id', 'id', 'id', 'team_id');
    }
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function endorser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'endorser_id');
    }
    public function executive_director() : BelongsTo
    {
        return $this->belongsTo(User::class, 'executive_director_id');
    }
    public function reviewer() : BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'proofreading_request_statuses', 'proofreading_request_id', 'user_id')->withTimestamps();
    }
    public function getExecutiveDirector()
    {
        return $this->reviewer()->where('type', 'executive director')->latest()->limit(1);
    }
    public function getProfessor()
    {
        return $this->reviewer()->where('type', 'professor')->latest()->limit(1);
    }
    public function getProofreader()
    {
        return $this->reviewer()->where('type', 'proofreader')->latest()->limit(1);
    }
    public function teamProjects()
    {
        $teams = UserTeam::where('user_id', auth()->id())->pluck('team_id')->toArray();
        return $this->belongsTo(ProjectSubmission::class, 'project_submission_id')->whereIn('team_id',$teams);
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

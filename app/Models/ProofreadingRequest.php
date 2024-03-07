<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProofreadingRequest extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'project_submission_id',
        'owner_id',
        'phone_number',
        'endorser_id',
        'executive_director_id',
        'number_pages',
        'number_words',
        'received_date',
        'released_date',
        'proofreader_id',
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

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function endorser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'endorser_id');
    }
    public function proofreader() : BelongsTo
    {
        return $this->belongsTo(User::class, 'proofreader_id');
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
    public function getRole()
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();
        return $roles;
    }
    public function getApprover()
    {
        return $this->belongsTo(User::class, 'user_id')
        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Executive Director');
    }
    public function getProofreaders()
    {
        return $this->belongsTo(User::class, 'user_id')
        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Proofreader');
    }
    public function getECHead()
    {
        return User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'English Cluster Head')
        ->get();
    }
}

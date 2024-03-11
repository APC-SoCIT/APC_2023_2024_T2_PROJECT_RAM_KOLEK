<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class ProjectSubmission extends Model
{
    use SoftDeletes;
    use HasFactory;
    use CascadesDeletes;



    protected $fillable = [
        'title',
        'abstract',
        'categories',
        'school',
        'program',
        'section',
        'subject',
        'academic_year',
        'professor_id',
        'attachments',
        'attachments_names',
        'team_id',
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
        return $this->hasManyThrough(User::class, UserTeam::class, 'team_id', 'members', 'id', 'id');
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
        return $this->hasOneThrough(ProofreadingRequestStatus::class, ProofreadingRequest::class, 'project_submission_id','proofreading_request_id',  'id','id')->latest();
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
    public function latestStatus() :HasOne
    {
        return $this->hasOne(ProjectSubmissionStatus::class, 'project_submission_id')->latest();
    }
    public function categories(): BelongsToMany
    {
        return $this->BelongsToMany(Categories::class, 'project_categories', 'project_submission_id', 'categories_id');
    }

}

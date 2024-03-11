<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Team extends Model
{
    use SoftDeletes;
    use HasFactory;
    use CascadesDeletes;

    protected $cascadeDeletes = ['projectSubmissions', 'proofreadingRequest'];

    protected $fillable = [
        'user_id',
        'name',
        'school',
        'section',
        'program',
        'academic_year',
    ];
    protected $casts = [
        'section' => 'array',
    ];
    public function getAllowed()
    {
        return $this->belongsTo(User::class, 'user_id')
        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Professor')
        ->orWhere('roles.name', 'PBL Coordinator');
    }
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function members() : BelongsToMany
    {
        $search = '@student';
        return $this->BelongsToMany(User::class, 'user_teams', 'team_id', 'user_id')->where('email','LIKE', "%{$search}%")->withTimestamps();
    }

    public function projectSubmissions() : HasMany
    {
        return $this->HasMany(ProjectSubmission::class, 'team_id');
    }
    public function proofreadingRequest() : HasMany
    {
        return $this->hasMany(ProofreadingRequest::class, 'team_id');
    }

    public function getMembers() : HasMany
    {
        $usersTeam = UserTeam::where('team_id', $this->record->id)->pluck('user_id')->toArray();
        $users =  User::whereIn('id', $usersTeam)->get();
        return $users;
    }
}



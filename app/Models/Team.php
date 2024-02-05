<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'members',
    ];
    protected $casts = [
        'members' => 'array',
    ];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function members() : HasMany
    {
        return $this->hasMany(User::class, 'members');
    }
    public function projectSubmissions() : HasMany
    {
        return $this->HasMany(ProjectSubmission::class, 'team_id');
    }
}



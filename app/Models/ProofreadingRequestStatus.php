<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProofreadingRequestStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'proofreading_request_id',
        'user_id',
        'type',
        'status',
        'feedback',
    ];

    protected $casts = [
        'attachments' => 'array',
        'attachments_names' => 'array',
    ];

    public function proofreadingRequest() : BelongsTo
    {
        return $this->belongsTo(ProofreadingRequest::class,'proofreading_request_id');
    }
    public function projectSubmission() : BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class,'proofreading_request_id');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function owner() : HasMany
    {
        return $this->hasMany(User::class,'owner');
    }
    public function endorser() : HasMany
    {
        return $this->hasMany(User::class,'endorser');
    }
    public function executiveDirector() : HasMany
    {
        return $this->hasMany(User::class,'executive_director');
    }
    public function proofreader() : HasMany
    {
        return $this->hasMany(User::class,'proofreader');
    }

}

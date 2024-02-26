<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function projectSubmission() :BelongsToMany
    {
        return $this->belongsToMany(ProjectSubmission::class, 'project_categories', 'project_submission_id', 'categories_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategories extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_submission_id',
        'categories_id',
    ];
}

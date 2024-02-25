<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable implements FilamentUser
{
    use HasRoles;
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@apc.edu.ph');
        }
        if ($panel->getId() === 'student') {
            return str_ends_with($this->email, '@student.apc.edu.ph');
        }
        if ($panel->getId() === 'faculty') {
            return str_ends_with($this->email, '@apc.edu.ph');
        }
 
        return true;
    }
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function projectSubmissionProfessor() :HasMany
    {
        return $this->hasMany(ProjectSubmission::class, 'professor_id');
    }

    public function teamAssigned() :BelongsTo
    {
        return $this->belongsTo(Team::class, 'team');
    }

    public function statusReviewed() :HasMany
    {
        return $this->hasMany(ProjectSubmissionStatus::class, 'user_id');
    }
}

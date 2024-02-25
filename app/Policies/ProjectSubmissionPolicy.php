<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProjectSubmission;
use App\Models\User;

class ProjectSubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProjectSubmission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectSubmission $projectsubmission): bool
    {
        return $user->checkPermissionTo('view ProjectSubmission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProjectSubmission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectSubmission $projectsubmission): bool
    {
        return $user->checkPermissionTo('update ProjectSubmission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectSubmission $projectsubmission): bool
    {
        return $user->checkPermissionTo('delete ProjectSubmission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectSubmission $projectsubmission): bool
    {
        return $user->checkPermissionTo('restore ProjectSubmission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectSubmission $projectsubmission): bool
    {
        return $user->checkPermissionTo('force-delete ProjectSubmission');
    }
}

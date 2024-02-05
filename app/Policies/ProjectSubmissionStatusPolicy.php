<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProjectSubmissionStatus;
use App\Models\User;

class ProjectSubmissionStatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectSubmissionStatus $projectsubmissionstatus): bool
    {
        return $user->checkPermissionTo('view ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectSubmissionStatus $projectsubmissionstatus): bool
    {
        return $user->checkPermissionTo('update ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectSubmissionStatus $projectsubmissionstatus): bool
    {
        return $user->checkPermissionTo('delete ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectSubmissionStatus $projectsubmissionstatus): bool
    {
        return $user->checkPermissionTo('restore ProjectSubmissionStatus');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectSubmissionStatus $projectsubmissionstatus): bool
    {
        return $user->checkPermissionTo('force-delete ProjectSubmissionStatus');
    }
}

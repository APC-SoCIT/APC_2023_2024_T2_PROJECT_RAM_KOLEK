<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProofreadingRequest;
use App\Models\User;

class ProofreadingRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProofreadingRequest');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProofreadingRequest $proofreadingrequest): bool
    {
        return $user->checkPermissionTo('view ProofreadingRequest');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProofreadingRequest');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProofreadingRequest $proofreadingrequest): bool
    {
        return $user->checkPermissionTo('update ProofreadingRequest');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProofreadingRequest $proofreadingrequest): bool
    {
        return $user->checkPermissionTo('delete ProofreadingRequest');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProofreadingRequest $proofreadingrequest): bool
    {
        return $user->checkPermissionTo('restore ProofreadingRequest');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProofreadingRequest $proofreadingrequest): bool
    {
        return $user->checkPermissionTo('force-delete ProofreadingRequest');
    }
}

<?php

declare(strict_types=1);

namespace App\Features\Logs\Policies;

use App\Features\Users\Models\User;
use App\Features\Logs\Models\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:Log');
    }

    public function view(User $authUser, Log $log): bool
    {
        return $authUser->can('View:Log');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:Log');
    }

    public function update(User $authUser, Log $log): bool
    {
        return $authUser->can('Update:Log');
    }

    public function delete(User $authUser, Log $log): bool
    {
        return $authUser->can('Delete:Log');
    }

    public function deleteAny(User $authUser): bool
    {
        return $authUser->can('DeleteAny:Log');
    }

    public function restore(User $authUser, Log $log): bool
    {
        return $authUser->can('Restore:Log');
    }

    public function forceDelete(User $authUser, Log $log): bool
    {
        return $authUser->can('ForceDelete:Log');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Log');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:Log');
    }

    public function replicate(User $authUser, Log $log): bool
    {
        return $authUser->can('Replicate:Log');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:Log');
    }
}
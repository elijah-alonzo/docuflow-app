<?php

declare(strict_types=1);

namespace App\Features\Logs\Policies;

use App\Features\Users\Models\User;
use App\Features\Logs\Models\SystemLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $authUser): bool
    {
        return $authUser->can('ViewAny:SystemLog');
    }

    public function view(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('View:SystemLog');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('Create:SystemLog');
    }

    public function update(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Update:SystemLog');
    }

    public function delete(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Delete:SystemLog');
    }

    public function deleteAny(User $authUser): bool
    {
        return $authUser->can('DeleteAny:SystemLog');
    }

    public function restore(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Restore:SystemLog');
    }

    public function forceDelete(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('ForceDelete:SystemLog');
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SystemLog');
    }

    public function restoreAny(User $authUser): bool
    {
        return $authUser->can('RestoreAny:SystemLog');
    }

    public function replicate(User $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Replicate:SystemLog');
    }

    public function reorder(User $authUser): bool
    {
        return $authUser->can('Reorder:SystemLog');
    }

}
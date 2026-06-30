<?php

namespace App\Features\DocumentSubmissions\Services;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserDashboardFeedResolver
{
    public function forUser(User $user): Collection
    {
        $roleNames = $user->roles->pluck('name');
        $isAdmin = $user->hasRole('Admin');

        return DocumentSubmission::query()
            ->where(function ($query) use ($user, $roleNames, $isAdmin) {
                $query->where('created_by', $user->id)
                    ->orWhereHas('uploaders', fn ($uploaderQuery) => $uploaderQuery->where('user_id', $user->id));

                if ($isAdmin) {
                    $query->orWhereNotNull('current_process_stage_id');
                } elseif ($roleNames->isNotEmpty()) {
                    $query->orWhereHas(
                        'currentProcessStage.role',
                        fn ($roleQuery) => $roleQuery->whereIn('name', $roleNames)
                    );
                }
            })
            ->with([
                'documentCategory.fields',
                'currentProcessStage.role',
                'createdBy',
                'uploaders',
            ])
            ->orderByDesc('updated_at')
            ->get();
    }
}
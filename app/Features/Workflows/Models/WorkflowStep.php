<?php

namespace App\Features\Workflows\Models;

use App\Features\Roles\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_id',
        'step_order',
        'step_name',
        'assigned_role_id',
        'action_label',
        'approve_status',
        'reject_status',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'assigned_role_id');
    }
}

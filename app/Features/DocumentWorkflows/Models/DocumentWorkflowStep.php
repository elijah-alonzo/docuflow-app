<?php

namespace App\Features\DocumentWorkflows\Models;

use App\Features\Roles\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentWorkflowStep extends Model
{
    protected $table = 'document_workflow_steps';

    protected $fillable = [
        'document_workflow_id',
        'step_order',
        'step_name',
        'assigned_role_id',
        'action_label',
        'approve_status',
        'reject_status',
    ];

    public function documentWorkflow(): BelongsTo
    {
        return $this->belongsTo(DocumentWorkflow::class, 'document_workflow_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'assigned_role_id');
    }
}

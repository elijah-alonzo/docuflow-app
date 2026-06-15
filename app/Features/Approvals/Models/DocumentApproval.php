<?php

namespace App\Features\Approvals\Models;

use App\Features\Workflows\Models\WorkflowStep;
use App\Features\Documents\Models\Document;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentApproval extends Model
{
    protected $fillable = [
        'document_id',
        'workflow_step_id',
        'approved_by',
        'status',
        'remarks',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

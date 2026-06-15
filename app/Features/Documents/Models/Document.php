<?php

namespace App\Features\Documents\Models;

use App\Features\Workflows\Models\Workflow;
use App\Features\Workflows\Models\WorkflowStep;
use App\Features\DocumentTypes\Models\DocumentType;
use App\Features\Users\Models\User;
use App\Features\Approvals\Models\DocumentApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'document_type_id',
        'workflow_id',
        'title',
        'file_path',
        'submitted_by',
        'status',
        'current_step_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }
}

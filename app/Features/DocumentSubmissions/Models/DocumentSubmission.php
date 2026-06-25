<?php

namespace App\Features\DocumentSubmissions\Models;

use App\Features\DocumentWorkflows\Models\DocumentWorkflow;
use App\Features\DocumentWorkflows\Models\DocumentWorkflowStep;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\Users\Models\User;
use App\Features\Approvals\Models\DocumentApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentSubmission extends Model
{
    protected $table = 'document_submissions';

    protected $fillable = [
        'document_category_id',
        'document_workflow_id',
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

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function documentWorkflow(): BelongsTo
    {
        return $this->belongsTo(DocumentWorkflow::class, 'document_workflow_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(DocumentWorkflowStep::class, 'current_step_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class, 'document_submission_id');
    }
}

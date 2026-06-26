<?php

namespace App\Features\DocumentSubmissions\Models;

use App\Features\DocumentWorkflows\Models\DocumentWorkflow;
use App\Features\DocumentWorkflows\Models\DocumentWorkflowStep;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\Users\Models\User;
use App\Features\Approvals\Models\DocumentApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentSubmission extends Model
{
    protected $table = 'document_submissions';

    protected $fillable = [
        'document_category_id',
        'document_workflow_id',
        'file_path',
        'created_by',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uploaders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'document_submission_uploaders', 'document_submission_id', 'user_id')
            ->withTimestamps();
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(DocumentWorkflowStep::class, 'current_step_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class, 'document_submission_id');
    }

    public function getDisplayNameAttribute(): string
    {
        $category = $this->documentCategory?->name ?? 'Document';
        $creator = $this->createdBy?->full_name ?? 'Unknown';
        $date = $this->created_at?->format('M d, Y') ?? '';

        return trim("{$category} — {$creator} ({$date})");
    }

    /**
     * Card title for the App panel dashboard: the value of the document
     * category's first configured field (lowest sort_order), falling back
     * to the category name when no value is set yet.
     */
    public function getCardTitleAttribute(): string
    {
        $firstField = $this->documentCategory?->fields?->first();

        if ($firstField) {
            $value = $this->metadata[$firstField->field_key] ?? null;

            if (filled($value)) {
                return (string) $value;
            }
        }

        return $this->documentCategory?->name ?? 'Document Submission';
    }

    /**
     * The "document type" line shown on the card — the category name.
     */
    public function getCardTypeAttribute(): string
    {
        return $this->documentCategory?->name ?? 'Document';
    }

    /**
     * Badge color for the status pill, matching the convention used in
     * DocumentSubmissionsTable.
     */
    public function getCardStatusColorAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'gray',
        };
    }
}
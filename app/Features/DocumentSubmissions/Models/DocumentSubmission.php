<?php

namespace App\Features\DocumentSubmissions\Models;

use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\Users\Models\User;
use App\Features\DocumentApprovals\Models\DocumentApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentSubmission extends Model
{
    protected $table = 'document_submissions';

    protected $fillable = [
        'document_category_id',
        'document_process_id',
        'file_path',
        'created_by',
        'status',
        'current_process_stage_id',
        'current_cycle',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'current_cycle' => 'integer',
    ];

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function documentProcess(): BelongsTo
    {
        return $this->belongsTo(DocumentProcess::class, 'document_process_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uploaders(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'document_submission_uploaders',
            'document_submission_id',
            'user_id'
        )->withTimestamps();
    }

    public function currentProcessStage(): BelongsTo
    {
        return $this->belongsTo(DocumentProcessStage::class, 'current_process_stage_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class, 'document_submission_id');
    }

    /**
     * Whether the assigned uploader(s)/creator may still edit the uploaded
     * file. Locked the moment the current cycle has at least one recorded
     * approval (the first stage approving closes the editing window).
     * Reopens automatically on rejection + restart, since restartProcess()
     * increments current_cycle, giving the new cycle a clean slate.
     */
    public function canEditFile(): bool
    {
        return ! $this->approvals()
            ->where('cycle', $this->current_cycle)
            ->where('status', 'approved')
            ->exists();
    }

    public function isUploaderOrCreator(User $user): bool
    {
        return $this->created_by === $user->id
            || $this->uploaders->contains('id', $user->id);
    }

    public function getDisplayNameAttribute(): string
    {
        $category = $this->documentCategory?->name ?? 'Document';
        $creator = $this->createdBy?->full_name ?? 'Unknown';
        $date = $this->created_at?->format('M d, Y') ?? '';

        return trim("{$category} — {$creator} ({$date})");
    }

    public function getCardTitleAttribute(): string
    {
        $firstField = $this->documentCategory?->fields?->first();

        if ($firstField) {
            $value = $this->metadata[$firstField->field_key] ?? null;

            if (filled($value)) {
                return (string) $value;
            }
        }

        return 'Untitled '.($this->documentCategory?->name ?? 'Document');
    }

    public function getCardTypeAttribute(): string
    {
        return $this->documentCategory?->name ?? 'Document';
    }

    public function getCardStatusColorAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'gray',
        };
    }

    public function getCardUploaderLabelAttribute(): string
    {
        $names = $this->uploaders->pluck('full_name');

        return $names->isNotEmpty() ? $names->implode(', ') : 'Unassigned';
    }
}
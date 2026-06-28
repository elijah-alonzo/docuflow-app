<?php

namespace App\Features\DocumentApprovals\Models;

use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentApproval extends Model
{
    protected $table = 'document_approvals';

    protected $fillable = [
        'document_submission_id',
        'document_process_stage_id',
        'approved_by',
        'status',
        'remarks',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    public function documentSubmission(): BelongsTo
    {
        return $this->belongsTo(DocumentSubmission::class, 'document_submission_id');
    }

    public function documentProcessStage(): BelongsTo
    {
        return $this->belongsTo(DocumentProcessStage::class, 'document_process_stage_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
<?php

namespace App\Features\DocumentApprovals\Services;

use App\Features\DocumentApprovals\Models\DocumentApproval;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\Users\Models\User;

class ApprovalService
{
    public function logAction(
        DocumentSubmission $document,
        ?DocumentProcessStage $stage,
        User $user,
        string $status,
        ?string $remarks = null
    ): DocumentApproval {
        return DocumentApproval::create([
            'document_submission_id' => $document->id,
            'document_process_stage_id' => $stage?->id,
            'approved_by' => $user->id,
            'status' => $status,
            'remarks' => $remarks,
            'acted_at' => now(),
        ]);
    }
}
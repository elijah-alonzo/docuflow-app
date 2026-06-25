<?php

namespace App\Features\Approvals\Services;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\Users\Models\User;
use App\Features\DocumentWorkflows\Models\DocumentWorkflowStep;
use App\Features\Approvals\Models\DocumentApproval;

class ApprovalService
{
    public function logAction(DocumentSubmission $document, ?DocumentWorkflowStep $step, User $user, string $status, ?string $remarks = null): DocumentApproval
    {
        return DocumentApproval::create([
            'document_submission_id' => $document->id,
            'document_workflow_step_id' => $step?->id,
            'approved_by' => $user->id,
            'status' => $status,
            'remarks' => $remarks,
            'acted_at' => now(),
        ]);
    }
}

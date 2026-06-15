<?php

namespace App\Features\Approvals\Services;

use App\Features\Documents\Models\Document;
use App\Features\Users\Models\User;
use App\Features\Workflows\Models\WorkflowStep;
use App\Features\Approvals\Models\DocumentApproval;

class ApprovalService
{
    public function logAction(Document $document, ?WorkflowStep $step, User $user, string $status, ?string $remarks = null): DocumentApproval
    {
        return DocumentApproval::create([
            'document_id' => $document->id,
            'workflow_step_id' => $step?->id,
            'approved_by' => $user->id,
            'status' => $status,
            'remarks' => $remarks,
            'acted_at' => now(),
        ]);
    }
}

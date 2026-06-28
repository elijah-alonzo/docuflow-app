<?php

namespace App\Features\DocumentProcesses\Services;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\Users\Models\User;
use App\Features\Roles\Models\Role;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\Approvals\Services\ApprovalService;
use App\Features\DocumentSubmissions\Services\DocumentSubmissionStatusService;

class DocumentProcessEngine
{
    protected ApprovalService $approvalService;
    protected DocumentSubmissionStatusService $statusService;

    public function __construct(
        ApprovalService $approvalService,
        DocumentSubmissionStatusService $statusService
    ) {
        $this->approvalService = $approvalService;
        $this->statusService = $statusService;
    }

    public function getCurrentStage(DocumentSubmission $document): ?DocumentProcessStage
    {
        if ($document->current_process_stage_id) {
            return $document->currentProcessStage;
        }

        if ($document->status === 'pending' || $document->status === 'in_progress') {
            return DocumentProcessStage::where('document_process_id', $document->document_process_id)
                ->orderBy('stage_order', 'asc')
                ->first();
        }

        return null;
    }

    public function getAvailableActions(DocumentSubmission $document, User $user): array
    {
        $stage = $this->getCurrentStage($document);

        if (!$stage) {
            return [];
        }

        if ($user->hasRole('Admin')) {
            return ['approve', 'reject'];
        }

        $role = Role::find($stage->assigned_role_id);

        if ($role && $user->hasRole($role->name)) {
            return ['approve', 'reject'];
        }

        return [];
    }

    public function approve(DocumentSubmission $document, User $user, ?string $remarks = null): bool
    {
        $stage = $this->getCurrentStage($document);

        if (!$stage) {
            return false;
        }

        $this->approvalService->logAction($document, $stage, $user, 'approved', $remarks);

        $nextStage = DocumentProcessStage::where('document_process_id', $document->document_process_id)
            ->where('stage_order', '>', $stage->stage_order)
            ->orderBy('stage_order', 'asc')
            ->first();

        if ($nextStage) {
            $document->update([
                'current_process_stage_id' => $nextStage->id,
                'status' => $stage->approve_status ?: 'in_progress',
            ]);
        } else {
            $document->update([
                'current_process_stage_id' => null,
                'status' => 'approved',
            ]);

            $this->statusService->markAsApproved($document);
        }

        return true;
    }

    public function reject(DocumentSubmission $document, User $user, ?string $remarks = null): bool
    {
        $stage = $this->getCurrentStage($document);

        if (!$stage) {
            return false;
        }

        $this->approvalService->logAction($document, $stage, $user, 'rejected', $remarks);

        $document->update([
            'current_process_stage_id' => null,
            'status' => $stage->reject_status ?: 'rejected',
        ]);

        $this->statusService->markAsRejected($document);

        return true;
    }

    public function moveToNextStage(DocumentSubmission $document): bool
    {
        $stage = $this->getCurrentStage($document);

        if (!$stage) {
            return false;
        }

        $nextStage = DocumentProcessStage::where('document_process_id', $document->document_process_id)
            ->where('stage_order', '>', $stage->stage_order)
            ->orderBy('stage_order', 'asc')
            ->first();

        if ($nextStage) {
            $document->update([
                'current_process_stage_id' => $nextStage->id,
            ]);

            return true;
        }

        $document->update([
            'current_process_stage_id' => null,
            'status' => 'approved',
        ]);

        return false;
    }

    public function restartProcess(DocumentSubmission $document): bool
    {
        $firstStage = DocumentProcessStage::where('document_process_id', $document->document_process_id)
            ->orderBy('stage_order', 'asc')
            ->first();

        if ($firstStage) {
            $document->update([
                'current_process_stage_id' => $firstStage->id,
                'status' => 'pending',
            ]);

            return true;
        }

        return false;
    }

    public function cancelProcess(DocumentSubmission $document): bool
    {
        $document->update([
            'current_process_stage_id' => null,
            'status' => 'cancelled',
        ]);

        return true;
    }
}
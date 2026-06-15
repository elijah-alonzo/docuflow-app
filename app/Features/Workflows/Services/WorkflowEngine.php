<?php

namespace App\Features\Workflows\Services;

use App\Features\Documents\Models\Document;
use App\Features\Users\Models\User;
use App\Features\Roles\Models\Role;
use App\Features\Workflows\Models\WorkflowStep;
use App\Features\Approvals\Services\ApprovalService;
use App\Features\Documents\Services\DocumentStatusService;

class WorkflowEngine
{
    protected ApprovalService $approvalService;
    protected DocumentStatusService $statusService;

    public function __construct(ApprovalService $approvalService, DocumentStatusService $statusService)
    {
        $this->approvalService = $approvalService;
        $this->statusService = $statusService;
    }

    public function getCurrentStep(Document $document): ?WorkflowStep
    {
        if ($document->current_step_id) {
            return $document->currentStep;
        }

        if ($document->status === 'pending' || $document->status === 'in_progress') {
            // Find the first step of the workflow
            return WorkflowStep::where('workflow_id', $document->workflow_id)
                ->orderBy('step_order', 'asc')
                ->first();
        }

        return null;
    }

    public function getAvailableActions(Document $document, User $user): array
    {
        $step = $this->getCurrentStep($document);

        if (!$step) {
            return [];
        }

        // Admin can do anything
        if ($user->hasRole('Admin')) {
            return ['approve', 'reject'];
        }

        // Check if user has the assigned role
        $role = Role::find($step->assigned_role_id);
        if ($role && $user->hasRole($role->name)) {
            return ['approve', 'reject'];
        }

        return [];
    }

    public function approve(Document $document, User $user, ?string $remarks = null): bool
    {
        $step = $this->getCurrentStep($document);
        if (!$step) {
            return false;
        }

        // Log the approval history
        $this->approvalService->logAction($document, $step, $user, 'approved', $remarks);

        // Determine next step
        $nextStep = WorkflowStep::where('workflow_id', $document->workflow_id)
            ->where('step_order', '>', $step->step_order)
            ->orderBy('step_order', 'asc')
            ->first();

        if ($nextStep) {
            $document->update([
                'current_step_id' => $nextStep->id,
                'status' => $step->approve_status ?: 'in_progress',
            ]);
        } else {
            // Finished!
            $document->update([
                'current_step_id' => null,
                'status' => 'approved',
            ]);
            $this->statusService->markAsApproved($document);
        }

        return true;
    }

    public function reject(Document $document, User $user, ?string $remarks = null): bool
    {
        $step = $this->getCurrentStep($document);
        if (!$step) {
            return false;
        }

        // Log the rejection history
        $this->approvalService->logAction($document, $step, $user, 'rejected', $remarks);

        // Rejection path sets the status and resets the current step so user can edit/resubmit
        $document->update([
            'current_step_id' => null,
            'status' => $step->reject_status ?: 'rejected',
        ]);

        $this->statusService->markAsRejected($document);

        return true;
    }

    public function moveToNextStep(Document $document): bool
    {
        $step = $this->getCurrentStep($document);
        if (!$step) {
            return false;
        }

        $nextStep = WorkflowStep::where('workflow_id', $document->workflow_id)
            ->where('step_order', '>', $step->step_order)
            ->orderBy('step_order', 'asc')
            ->first();

        if ($nextStep) {
            $document->update([
                'current_step_id' => $nextStep->id,
            ]);
            return true;
        }

        $document->update([
            'current_step_id' => null,
            'status' => 'approved',
        ]);
        return false;
    }

    public function restartWorkflow(Document $document): bool
    {
        $firstStep = WorkflowStep::where('workflow_id', $document->workflow_id)
            ->orderBy('step_order', 'asc')
            ->first();

        if ($firstStep) {
            $document->update([
                'current_step_id' => $firstStep->id,
                'status' => 'pending',
            ]);
            return true;
        }

        return false;
    }

    public function cancelWorkflow(Document $document): bool
    {
        $document->update([
            'current_step_id' => null,
            'status' => 'cancelled',
        ]);

        return true;
    }
}

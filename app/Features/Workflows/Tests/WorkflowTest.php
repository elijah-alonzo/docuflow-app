<?php

namespace App\Features\Workflows\Tests;

use App\Features\Workflows\Models\Workflow;
use App\Features\Workflows\Models\WorkflowStep;
use App\Features\Documents\Models\Document;
use App\Features\DocumentTypes\Models\DocumentType;
use App\Features\Roles\Models\Role;
use App\Features\Users\Models\User;
use App\Features\Workflows\Services\WorkflowEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_workflow_steps_transition_and_approval_engine()
    {
        // 1. Setup Roles
        $roleStaff = Role::create(['name' => 'Staff', 'guard_name' => 'web']);
        $roleDean = Role::create(['name' => 'Dean', 'guard_name' => 'web']);

        // 2. Setup Users
        $staffUser = User::create([
            'first_name' => 'Staff',
            'last_name' => 'User',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
        ]);
        $staffUser->assignRole($roleStaff);

        $deanUser = User::create([
            'first_name' => 'Dean',
            'last_name' => 'User',
            'email' => 'dean@test.com',
            'password' => bcrypt('password'),
        ]);
        $deanUser->assignRole($roleDean);

        // 3. Setup Workflow Template
        $workflow = Workflow::create([
            'name' => 'Grading Workflow',
            'description' => 'Grading sheets approval template',
        ]);

        $step1 = WorkflowStep::create([
            'workflow_id' => $workflow->id,
            'step_order' => 1,
            'step_name' => 'Staff Endorsement',
            'assigned_role_id' => $roleStaff->id,
            'action_label' => 'Endorse',
            'approve_status' => 'to_verify',
            'reject_status' => 'returned',
        ]);

        $step2 = WorkflowStep::create([
            'workflow_id' => $workflow->id,
            'step_order' => 2,
            'step_name' => 'Dean Approval',
            'assigned_role_id' => $roleDean->id,
            'action_label' => 'Approve',
            'approve_status' => 'approved',
            'reject_status' => 'returned',
        ]);

        // 4. Setup Document Type
        $docType = DocumentType::create([
            'name' => 'Grading Sheet',
            'workflow_id' => $workflow->id,
            'is_active' => true,
        ]);

        // 5. Create Document Submission
        $document = Document::create([
            'document_type_id' => $docType->id,
            'workflow_id' => $workflow->id,
            'title' => 'First Semester Grades',
            'file_path' => 'documents/grades.pdf',
            'submitted_by' => $staffUser->id,
            'status' => 'pending',
            'current_step_id' => $step1->id,
        ]);

        /** @var WorkflowEngine $engine */
        $engine = app(WorkflowEngine::class);

        // 6. Test current step
        $this->assertEquals($step1->id, $engine->getCurrentStep($document)->id);

        // 7. Check staff available actions
        $actions = $engine->getAvailableActions($document, $staffUser);
        $this->assertContains('approve', $actions);
        $this->assertContains('reject', $actions);

        // Dean shouldn't have actions at step 1
        $actionsDean = $engine->getAvailableActions($document, $deanUser);
        $this->assertEmpty($actionsDean);

        // 8. Staff Approves Step 1
        $engine->approve($document, $staffUser, 'Looks good, endorsing.');

        $document->refresh();
        $this->assertEquals($step2->id, $document->current_step_id);
        $this->assertEquals('to_verify', $document->status);

        // 9. Dean Approves Step 2
        $engine->approve($document, $deanUser, 'Fully approved.');

        $document->refresh();
        $this->assertNull($document->current_step_id);
        $this->assertEquals('approved', $document->status);

        // 10. Audit log entries
        $this->assertCount(2, $document->approvals);
        $this->assertEquals('Looks good, endorsing.', $document->approvals->first()->remarks);
        $this->assertEquals('Fully approved.', $document->approvals->last()->remarks);
    }
}

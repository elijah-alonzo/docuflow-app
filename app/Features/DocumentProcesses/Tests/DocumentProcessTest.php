<?php

namespace App\Features\DocumentProcesses\Tests;

use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
use App\Features\DocumentProcesses\Services\DocumentProcessEngine;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\Roles\Models\Role;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_stages_transition_and_approval_engine()
    {
        $roleStaff = Role::create([
            'name' => 'Staff',
            'guard_name' => 'web',
        ]);

        $roleDean = Role::create([
            'name' => 'Dean',
            'guard_name' => 'web',
        ]);

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

        $uploaderUser = User::create([
            'first_name' => 'Uploader',
            'last_name' => 'User',
            'email' => 'uploader@test.com',
            'password' => bcrypt('password'),
        ]);

        $uploaderUser->assignRole($roleStaff);

        $process = DocumentProcess::create([
            'name' => 'Grading Process',
            'description' => 'Grading sheets approval process',
        ]);

        $stage1 = DocumentProcessStage::create([
            'document_process_id' => $process->id,
            'stage_order' => 1,
            'stage_name' => 'Staff Endorsement',
            'assigned_role_id' => $roleStaff->id,
            'action_label' => 'Endorse',
            'approve_status' => 'to_verify',
            'reject_status' => 'returned',
        ]);

        $stage2 = DocumentProcessStage::create([
            'document_process_id' => $process->id,
            'stage_order' => 2,
            'stage_name' => 'Dean Approval',
            'assigned_role_id' => $roleDean->id,
            'action_label' => 'Approve',
            'approve_status' => 'approved',
            'reject_status' => 'returned',
        ]);

        $documentCategory = DocumentCategory::create([
            'name' => 'Grading Sheet',
            'document_process_id' => $process->id,
            'is_active' => true,
        ]);

        $document = DocumentSubmission::create([
            'document_category_id' => $documentCategory->id,
            'document_process_id' => $process->id,
            'file_path' => 'documents/grades.pdf',
            'created_by' => $staffUser->id,
            'status' => 'pending',
            'current_process_stage_id' => $stage1->id,
        ]);

        $document->uploaders()->sync([$uploaderUser->id]);

        /** @var DocumentProcessEngine $engine */
        $engine = app(DocumentProcessEngine::class);

        $this->assertEquals($stage1->id, $engine->getCurrentStage($document)->id);

        $actions = $engine->getAvailableActions($document, $staffUser);
        $this->assertContains('approve', $actions);
        $this->assertContains('reject', $actions);

        $actionsDean = $engine->getAvailableActions($document, $deanUser);
        $this->assertEmpty($actionsDean);

        $engine->approve($document, $staffUser, 'Looks good, endorsing.');

        $document->refresh();

        $this->assertEquals($stage2->id, $document->current_process_stage_id);
        $this->assertEquals('to_verify', $document->status);

        $engine->approve($document, $deanUser, 'Fully approved.');

        $document->refresh();

        $this->assertNull($document->current_process_stage_id);
        $this->assertEquals('approved', $document->status);

        $this->assertCount(2, $document->approvals);
        $this->assertEquals('Looks good, endorsing.', $document->approvals->first()->remarks);
        $this->assertEquals('Fully approved.', $document->approvals->last()->remarks);

        $this->assertEquals($staffUser->id, $document->created_by);
        $this->assertTrue($document->uploaders->contains($uploaderUser));
    }
}
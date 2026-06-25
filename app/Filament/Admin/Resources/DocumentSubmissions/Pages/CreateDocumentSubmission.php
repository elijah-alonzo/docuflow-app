<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Pages;

use App\Filament\Admin\Resources\DocumentSubmissions\DocumentSubmissionResource;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\DocumentWorkflows\Models\DocumentWorkflowStep;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentSubmission extends CreateRecord
{
    protected static string $resource = DocumentSubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['submitted_by'])) {
            $data['submitted_by'] = auth()->id();
        }
        $data['status'] = 'pending';

        if (empty($data['document_workflow_id'])) {
            $documentCategory = DocumentCategory::find($data['document_category_id']);
            if ($documentCategory && $documentCategory->document_workflow_id) {
                $data['document_workflow_id'] = $documentCategory->document_workflow_id;
            }
        }

        if (!empty($data['document_workflow_id'])) {
            $firstStep = DocumentWorkflowStep::where('document_workflow_id', $data['document_workflow_id'])
                ->orderBy('step_order', 'asc')
                ->first();

            if ($firstStep) {
                $data['current_step_id'] = $firstStep->id;
            }
        }

        return $data;
    }
}

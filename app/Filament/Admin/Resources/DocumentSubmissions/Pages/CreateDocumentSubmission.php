<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Pages;

use App\Filament\Admin\Resources\DocumentSubmissions\DocumentSubmissionResource;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\DocumentWorkflows\Models\DocumentWorkflowStep;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;


class CreateDocumentSubmission extends CreateRecord
{
    protected static string $resource = DocumentSubmissionResource::class;

    protected static bool $canCreateAnother = false;

    protected array $uploaderIds = [];

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['status'] = 'pending';

        $this->uploaderIds = (array) ($data['uploaders'] ?? []);
        unset($data['uploaders']);

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

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        if (!empty($this->uploaderIds)) {
            $record->uploaders()->sync($this->uploaderIds);
        }

        return $record;
    }
}
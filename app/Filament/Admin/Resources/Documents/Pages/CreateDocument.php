<?php

namespace App\Filament\Admin\Resources\Documents\Pages;

use App\Filament\Admin\Resources\Documents\DocumentsResource;
use App\Features\DocumentTypes\Models\DocumentType;
use App\Features\Workflows\Models\WorkflowStep;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentsResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = auth()->id();
        $data['status'] = 'pending';

        $documentType = DocumentType::find($data['document_type_id']);
        if ($documentType && $documentType->workflow_id) {
            $data['workflow_id'] = $documentType->workflow_id;

            $firstStep = WorkflowStep::where('workflow_id', $documentType->workflow_id)
                ->orderBy('step_order', 'asc')
                ->first();

            if ($firstStep) {
                $data['current_step_id'] = $firstStep->id;
            }
        }

        return $data;
    }
}

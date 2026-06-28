<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Pages;

use App\Filament\Admin\Resources\DocumentSubmissions\DocumentSubmissionResource;
use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\DocumentProcesses\Models\DocumentProcessStage;
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

        if (empty($data['document_process_id'])) {
            $documentCategory = DocumentCategory::find($data['document_category_id']);
            if ($documentCategory && $documentCategory->document_process_id) {
                $data['document_process_id'] = $documentCategory->document_process_id;
            }
        }

        if (!empty($data['document_process_id'])) {
            $firstStage = DocumentProcessStage::where('document_process_id', $data['document_process_id'])
                ->orderBy('stage_order', 'asc')
                ->first();

            if ($firstStage) {
                $data['current_process_stage_id'] = $firstStage->id;
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
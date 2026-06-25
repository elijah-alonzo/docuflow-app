<?php

namespace App\Filament\Admin\Resources\DocumentWorkflows\Pages;

use App\Filament\Admin\Resources\DocumentWorkflows\DocumentWorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentWorkflow extends CreateRecord
{
    protected static string $resource = DocumentWorkflowResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

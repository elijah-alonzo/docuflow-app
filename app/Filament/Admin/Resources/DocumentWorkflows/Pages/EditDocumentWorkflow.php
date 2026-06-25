<?php

namespace App\Filament\Admin\Resources\DocumentWorkflows\Pages;

use App\Filament\Admin\Resources\DocumentWorkflows\DocumentWorkflowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentWorkflow extends EditRecord
{
    protected static string $resource = DocumentWorkflowResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

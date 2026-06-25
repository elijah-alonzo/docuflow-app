<?php

namespace App\Filament\Admin\Resources\DocumentWorkflows\Pages;

use App\Filament\Admin\Resources\DocumentWorkflows\DocumentWorkflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentWorkflows extends ListRecords
{
    protected static string $resource = DocumentWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

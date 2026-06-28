<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\Pages;

use App\Filament\Admin\Resources\DocumentProcesses\DocumentProcessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentProcesses extends ListRecords
{
    protected static string $resource = DocumentProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
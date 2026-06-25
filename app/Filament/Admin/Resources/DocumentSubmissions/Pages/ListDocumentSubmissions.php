<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Pages;

use App\Filament\Admin\Resources\DocumentSubmissions\DocumentSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentSubmissions extends ListRecords
{
    protected static string $resource = DocumentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

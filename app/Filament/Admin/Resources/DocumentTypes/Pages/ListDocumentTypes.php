<?php

namespace App\Filament\Admin\Resources\DocumentTypes\Pages;

use App\Filament\Admin\Resources\DocumentTypes\DocumentType;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentTypes extends ListRecords
{
    protected static string $resource = DocumentType::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

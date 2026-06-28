<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\Pages;

use App\Filament\Admin\Resources\DocumentProcesses\DocumentProcessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentProcess extends EditRecord
{
    protected static string $resource = DocumentProcessResource::class;

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
<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\Pages;

use App\Filament\Admin\Resources\DocumentProcesses\DocumentProcessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentProcess extends CreateRecord
{
    protected static string $resource = DocumentProcessResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Pages;

use App\Filament\Admin\Resources\DocumentSubmissions\DocumentSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentSubmission extends EditRecord
{
    protected static string $resource = DocumentSubmissionResource::class;

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

<?php

namespace App\Filament\Admin\Resources\DocumentCategories\Pages;

use App\Filament\Admin\Resources\DocumentCategories\DocumentCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentCategory extends CreateRecord
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Admin\Resources\DocumentTypes\Pages;

use App\Filament\Admin\Resources\DocumentTypes\DocumentType;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentType extends CreateRecord
{
    protected static string $resource = DocumentType::class;
}

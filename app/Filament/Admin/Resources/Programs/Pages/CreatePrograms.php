<?php

namespace App\Filament\Admin\Resources\Programs\Pages;

use App\Filament\Admin\Resources\Programs\ProgramsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrograms extends CreateRecord
{
    protected static string $resource = ProgramsResource::class;

    protected ?string $subheading = 'Create a new program and define its details.';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

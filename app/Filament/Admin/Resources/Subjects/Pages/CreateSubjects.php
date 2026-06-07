<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use App\Filament\Admin\Resources\Subjects\SubjectsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubjects extends CreateRecord
{
    protected static string $resource = SubjectsResource::class;

    protected ?string $subheading = 'Create a new subject offering.';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

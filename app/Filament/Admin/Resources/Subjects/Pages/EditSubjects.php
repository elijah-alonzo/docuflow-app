<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use App\Filament\Admin\Resources\Subjects\SubjectsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubjects extends EditRecord
{
    protected static string $resource = SubjectsResource::class;

    protected ?string $subheading = 'Edit subject details.';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

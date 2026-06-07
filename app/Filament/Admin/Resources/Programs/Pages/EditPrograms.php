<?php

namespace App\Filament\Admin\Resources\Programs\Pages;

use App\Filament\Admin\Resources\Programs\ProgramsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrograms extends EditRecord
{
    protected static string $resource = ProgramsResource::class;

    protected ?string $subheading = 'Edit program details and manage its subjects.';

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

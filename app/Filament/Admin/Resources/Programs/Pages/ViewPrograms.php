<?php

namespace App\Filament\Admin\Resources\Programs\Pages;

use App\Filament\Admin\Resources\Programs\ProgramsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrograms extends ViewRecord
{
    protected static string $resource = ProgramsResource::class;

    protected ?string $subheading = 'View program details and its associated subjects.';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

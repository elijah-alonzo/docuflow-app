<?php

namespace App\Filament\Admin\Resources\AcademicYears\Pages;

use App\Filament\Admin\Resources\AcademicYears\AcademicYearsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcademicYears extends ListRecords
{
    protected static string $resource = AcademicYearsResource::class;

    protected ?string $subheading = 'Browse and manage academic year records.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

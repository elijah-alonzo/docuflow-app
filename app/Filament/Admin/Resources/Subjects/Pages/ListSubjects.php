<?php

namespace App\Filament\Admin\Resources\Subjects\Pages;

use App\Filament\Admin\Resources\Subjects\SubjectsResource;
use App\Filament\Admin\Resources\Subjects\Widgets\SubjectsStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectsResource::class;

    protected ?string $subheading = 'Browse, create, and manage subjects offered.';

    protected function getHeaderWidgets(): array
    {
        return [
            SubjectsStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

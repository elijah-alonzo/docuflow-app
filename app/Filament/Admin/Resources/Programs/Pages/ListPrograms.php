<?php

namespace App\Filament\Admin\Resources\Programs\Pages;

use App\Filament\Admin\Resources\Programs\ProgramsResource;
use App\Filament\Admin\Resources\Programs\Widgets\ProgramsStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListPrograms extends ListRecords
{
    protected static string $resource = ProgramsResource::class;

    protected ?string $subheading = 'Browse, create, and manage graduate programs.';

    protected function getHeaderWidgets(): array
    {
        return [
            ProgramsStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'doctoral' => Tab::make('Doctoral')
                ->modifyQueryUsing(fn ($query) => $query->where('degree', 'Doctoral')),
            'masteral' => Tab::make('Masteral')
                ->modifyQueryUsing(fn ($query) => $query->where('degree', 'Masteral')),
        ];
    }
}

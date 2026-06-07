<?php

namespace App\Filament\Admin\Resources\Programs\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProgramsStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 2;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Programs', Program::count())
                ->description('Total programs available')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-academic-cap'),
            Stat::make('Active Programs', Program::where('is_active', true)->count())
                ->description('Programs currently active')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-check-badge'),
        ];
    }
}

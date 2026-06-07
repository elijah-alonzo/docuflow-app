<?php

namespace App\Filament\Admin\Resources\Subjects\Widgets;

use App\Models\Subject;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubjectsStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Total Subjects', Subject::count())
                ->description('All subjects in the system')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Active Subjects', Subject::where('is_active', true)->count())
                ->description('Subjects currently active')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-check-badge'),
        ];
    }
}

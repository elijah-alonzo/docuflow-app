<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AcademicYear;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AcademicContextWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can('View:AcademicContextWidget') ?? false;
    }

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getCards(): array
    {
        $currentYear = AcademicYear::current();

        return [
            Stat::make('Academic Year', $currentYear?->year ?? 'N/A')
                ->description('Current academic year')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary')
                ->chart([2, 3, 4, 3, 5, 4, 6]),
        ];
    }
}

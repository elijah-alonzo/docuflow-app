<?php

namespace App\Filament\Admin\Resources\Users\Widgets;

use App\Features\Users\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-user-group'),
            Stat::make('Users With Roles', User::has('roles')->count())
                ->description('Assigned to at least one role')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-users'),
            Stat::make('Users Without Roles', User::doesntHave('roles')->count())
                ->description('No role assigned yet')
                ->color('primary')
                ->chart([1, 4, 2, 4, 5, 6, 7])
                ->descriptionIcon('heroicon-o-user'),

        ];
    }
}

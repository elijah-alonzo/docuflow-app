<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Load;
use App\Models\RegistrationRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Dean', 'Staff', 'Registrar']);
    }

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        $users = User::count();
        $unsubmitted = Load::query()->where('grading_sheet_status', 'pending')->count();
        $registrationRequests = RegistrationRequest::query()->where('status', 'pending')->count();

        return [
            Stat::make('Users', $users)
                ->description('Total registered users')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->chart([2, 5, 3, 6, 4, 7, 6]),
            Stat::make('Unsubmitted', $unsubmitted)
                ->description('Pending grading sheets')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([1, 4, 2, 5, 3, 4, 5]),
            Stat::make('Requests', $registrationRequests)
                ->description('Account registration requests')
                ->descriptionIcon('heroicon-o-inbox')
                ->color('primary')
                ->chart([0, 1, 0, 2, 1, 2, 1]),
        ];
    }
}

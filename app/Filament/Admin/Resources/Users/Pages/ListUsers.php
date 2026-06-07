<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Admin\Resources\Users\Widgets\UsersStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected ?string $subheading = 'Browse, create, and manage users.';

    protected function getHeaderWidgets(): array
    {
        return [
            UsersStatsWidget::class,
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
        $tabs = [
            'all' => Tab::make('All'),
        ];

        $roles = Role::query()
            ->whereNotIn('name', [
                'super admin',
                'Super Admin',
                'super-admin',
                'Super-Admin',
                'Admin',
                'admin',
            ])
            ->orderBy('name')
            ->pluck('name');

        foreach ($roles as $role) {
            $tabs[$role] = Tab::make($role)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn (Builder $rolesQuery) => $rolesQuery->where('name', $role)));
        }

        return $tabs;
    }
}

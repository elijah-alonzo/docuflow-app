<?php

namespace App\Filament\Admin\Resources\Loads\Pages;

use App\Filament\Admin\Resources\Loads\LoadsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListLoads extends ListRecords
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'Browse, create, and manage faculty loads.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $user = Auth::user();

        if (! $user || ! static::getResource()::canViewAny()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->with(['program', 'subject', 'user', 'academicYear']);
    }
}

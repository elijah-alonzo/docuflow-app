<?php

namespace App\Filament\Admin\Resources\RegistrationRequests;

use App\Filament\Admin\Resources\RegistrationRequests\Pages\ListRegistrationRequests;
use App\Filament\Admin\Resources\RegistrationRequests\Pages\ViewRegistrationRequest;
use App\Filament\Admin\Resources\RegistrationRequests\Tables\RegistrationRequestsTable;
use App\Models\RegistrationRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RegistrationRequestsResource extends Resource
{
    protected static ?string $model = RegistrationRequest::class;

    protected static UnitEnum|string|null $navigationGroup = 'User Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Registration Requests';

    protected static ?int $navigationSort = 20;

    public static function table(Table $table): Table
    {
        return RegistrationRequestsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return static::canManageRequests();
    }

    public static function canView(Model $record): bool
    {
        return static::canManageRequests();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function canManageRequests(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Dean', 'Staff']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrationRequests::route('/'),
            'view' => ViewRegistrationRequest::route('/{record}'),
        ];
    }
}

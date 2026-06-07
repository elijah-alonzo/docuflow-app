<?php

namespace App\Filament\Admin\Resources\Loads;

use App\Filament\Admin\Resources\Loads\Pages\CreateLoads;
use App\Filament\Admin\Resources\Loads\Pages\EditLoads;
use App\Filament\Admin\Resources\Loads\Pages\ListLoads;
use App\Filament\Admin\Resources\Loads\Pages\ViewLoads;
use App\Filament\Admin\Resources\Loads\Schemas\LoadsForm;
use App\Filament\Admin\Resources\Loads\Tables\LoadsTable;
use App\Models\Load;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LoadsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Faculty Loads';

    protected static function canManageLoads(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Dean', 'Staff', 'Registrar'])
            || $user->can('ManageFacultyLoads');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->hasRole('Registrar') && $user->program_id) {
            return $query->where('program_id', $user->program_id);
        }

        return $query;
    }

    public static function canAccess(): bool
    {
        return static::canManageLoads();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canManageLoads();
    }

    public static function canViewAny(): bool
    {
        return static::canManageLoads();
    }

    public static function canCreate(): bool
    {
        return static::canManageLoads();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canManageLoads();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canManageLoads();
    }

    public static function form(Schema $schema): Schema
    {
        return LoadsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoadsTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return 'Faculty Load';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Faculty Loads';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoads::route('/'),
            'create' => CreateLoads::route('/create'),
            'view' => ViewLoads::route('/{record}'),
            'edit' => EditLoads::route('/{record}/edit'),
        ];
    }
}

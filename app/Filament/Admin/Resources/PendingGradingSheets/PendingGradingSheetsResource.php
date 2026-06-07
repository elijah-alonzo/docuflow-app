<?php

namespace App\Filament\Admin\Resources\PendingGradingSheets;

use App\Filament\Admin\Resources\PendingGradingSheets\Pages\ListPendingGradingSheets;
use App\Filament\Admin\Resources\PendingGradingSheets\Pages\ViewPendingGradingSheet;
use App\Filament\Admin\Resources\PendingGradingSheets\Tables\PendingGradingSheetsTable;
use App\Models\Load;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PendingGradingSheetsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static ?string $navigationLabel = 'Faculty Grading Sheets';

    protected static ?int $navigationSort = 21;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return PendingGradingSheetsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('grading_sheet_status', 'to_endorse');
    }

    public static function getModelLabel(): string
    {
        return 'Faculty Grading Sheet';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Faculty Grading Sheets';
    }

    public static function canAccess(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('ViewAny:PendingGradingSheet') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('View:PendingGradingSheet') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPendingGradingSheets::route('/'),
            'view' => ViewPendingGradingSheet::route('/{record}'),
        ];
    }
}

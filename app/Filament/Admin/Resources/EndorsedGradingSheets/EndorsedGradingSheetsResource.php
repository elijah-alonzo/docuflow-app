<?php

namespace App\Filament\Admin\Resources\EndorsedGradingSheets;

use App\Filament\Admin\Resources\EndorsedGradingSheets\Pages\ListEndorsedGradingSheets;
use App\Filament\Admin\Resources\EndorsedGradingSheets\Pages\ViewEndorsedGradingSheet;
use App\Filament\Admin\Resources\EndorsedGradingSheets\Tables\EndorsedGradingSheetsTable;
use App\Models\Load;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class EndorsedGradingSheetsResource extends Resource
{
    protected static ?string $model = Load::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static UnitEnum|string|null $navigationGroup = 'Grading Sheet Management';

    protected static ?string $navigationLabel = 'Endorsed Grading Sheets';

    protected static ?int $navigationSort = 22;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return EndorsedGradingSheetsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('grading_sheet_status', 'to_verify');
    }

    public static function getModelLabel(): string
    {
        return 'Endorsed Grading Sheet';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Endorsed Grading Sheets';
    }

    public static function canAccess(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('ViewAny:EndorsedGradingSheet') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('View:EndorsedGradingSheet') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEndorsedGradingSheets::route('/'),
            'view' => ViewEndorsedGradingSheet::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\Logs;

use App\Filament\Admin\Resources\Logs\Pages\ListLogs;
use App\Filament\Admin\Resources\Logs\Tables\LogsTable;
use App\Features\Logs\Models\Log;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LogsResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static UnitEnum|string|null $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'System Logs';

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return LogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogs::route('/'),
        ];
    }
}

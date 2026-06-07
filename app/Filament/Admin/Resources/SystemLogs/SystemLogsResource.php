<?php

namespace App\Filament\Admin\Resources\SystemLogs;

use App\Filament\Admin\Resources\SystemLogs\Pages\ListSystemLogs;
use App\Filament\Admin\Resources\SystemLogs\Tables\SystemLogsTable;
use App\Models\SystemLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SystemLogsResource extends Resource
{
    protected static ?string $model = SystemLog::class;

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
        return SystemLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSystemLogs::route('/'),
        ];
    }
}

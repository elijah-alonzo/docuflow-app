<?php

namespace App\Filament\Admin\Resources\DocumentProcesses;

use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Filament\Admin\Resources\DocumentProcesses\Pages\CreateDocumentProcess;
use App\Filament\Admin\Resources\DocumentProcesses\Pages\EditDocumentProcess;
use App\Filament\Admin\Resources\DocumentProcesses\Pages\ListDocumentProcesses;
use App\Filament\Admin\Resources\DocumentProcesses\Schemas\DocumentProcessForm;
use App\Filament\Admin\Resources\DocumentProcesses\Tables\DocumentProcessesTable;
use App\Filament\Admin\Resources\DocumentProcesses\RelationManagers\DocumentProcessStages;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DocumentProcessResource extends Resource
{
    protected static ?string $model = DocumentProcess::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Document Processes';

    protected static ?int $navigationSort = 30;

    public static function getModelLabel(): string
    {
        return 'Document Process';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Document Processes';
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentProcessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentProcessesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentProcesses::route('/'),
            'create' => CreateDocumentProcess::route('/create'),
            'edit' => EditDocumentProcess::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DocumentProcessStages::class,
        ];
    }
}
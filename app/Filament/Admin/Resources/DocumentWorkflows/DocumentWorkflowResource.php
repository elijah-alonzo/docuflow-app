<?php

namespace App\Filament\Admin\Resources\DocumentWorkflows;

use App\Features\DocumentWorkflows\Models\DocumentWorkflow;
use App\Filament\Admin\Resources\DocumentWorkflows\Pages\CreateDocumentWorkflow;
use App\Filament\Admin\Resources\DocumentWorkflows\Pages\EditDocumentWorkflow;
use App\Filament\Admin\Resources\DocumentWorkflows\Pages\ListDocumentWorkflows;
use App\Filament\Admin\Resources\DocumentWorkflows\Schemas\DocumentWorkflowForm;
use App\Filament\Admin\Resources\DocumentWorkflows\Tables\DocumentWorkflowsTable;
use App\Filament\Admin\Resources\DocumentWorkflows\RelationManagers\DocumentWorkflowStepsRelationManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DocumentWorkflowResource extends Resource
{
    protected static ?string $model = DocumentWorkflow::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Document Workflows';

    protected static ?int $navigationSort = 30;

    public static function getModelLabel(): string
    {
        return 'Document Workflow';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Document Workflows';
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentWorkflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentWorkflowsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentWorkflows::route('/'),
            'create' => CreateDocumentWorkflow::route('/create'),
            'edit' => EditDocumentWorkflow::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DocumentWorkflowStepsRelationManager::class,
        ];
    }
}

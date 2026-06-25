<?php

namespace App\Filament\Admin\Resources\Workflows;

use App\Features\Workflows\Models\Workflow;
use App\Filament\Admin\Resources\Workflows\Pages\CreateWorkflow;
use App\Filament\Admin\Resources\Workflows\Pages\EditWorkflow;
use App\Filament\Admin\Resources\Workflows\Pages\ListWorkflows;
use App\Filament\Admin\Resources\Workflows\Schemas\WorkflowForm;
use App\Filament\Admin\Resources\Workflows\Tables\WorkflowsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WorkflowsResource extends Resource
{
    protected static ?string $model = Workflow::class;

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
        return WorkflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkflowStepsRelationManager::class,
        ];
    }
}
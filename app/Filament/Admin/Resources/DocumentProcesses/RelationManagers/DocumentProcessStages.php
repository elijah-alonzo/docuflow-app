<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\RelationManagers;

use App\Filament\Admin\Resources\DocumentProcesses\RelationManagers\Schemas\DocumentProcessStagesForm;
use App\Filament\Admin\Resources\DocumentProcesses\RelationManagers\Tables\DocumentProcessStagesTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DocumentProcessStages extends RelationManager
{
    protected static string $relationship = 'stages';

    protected static ?string $title = 'Document Process Stages';

    public function form(Schema $schema): Schema
    {
        return DocumentProcessStagesForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return DocumentProcessStagesTable::configure($table);
    }
}
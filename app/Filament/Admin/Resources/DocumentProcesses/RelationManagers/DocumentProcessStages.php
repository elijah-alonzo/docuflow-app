<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\RelationManagers;

use App\Features\Roles\Models\Role;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentProcessStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'stages';

    protected static ?string $title = 'Document Process Stages';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\TextInput::make('stage_name')
                    ->label('Stage Name')
                    ->placeholder('e.g. Manager Approval')
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('assigned_role_id')
                    ->label('Assigned Role')
                    ->options(Role::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('action_label')
                    ->label('Action Button Label')
                    ->placeholder('e.g. Approve, Endorse')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('approve_status')
                    ->label('Approved Status')
                    ->placeholder('e.g. Approved by Manager')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('reject_status')
                    ->label('Rejected Status')
                    ->placeholder('e.g. Rejected by Manager')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('stage_order')
            ->defaultSort('stage_order')
            ->description('Configure the stages for the document processing.')
            ->columns([
                Tables\Columns\TextColumn::make('stage_order')
                    ->label('#')
                    ->width(40)
                    ->sortable(),

                Tables\Columns\TextColumn::make('stage_name')
                    ->label('Stage Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label('Assigned Role'),

                Tables\Columns\TextColumn::make('action_label')
                    ->label('Action Label'),

                Tables\Columns\TextColumn::make('approve_status')
                    ->label('Approved Status')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('reject_status')
                    ->label('Rejected Status')
                    ->badge()
                    ->color('danger'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Stage')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['stage_order'] = $this->getOwnerRecord()->stages()->count() + 1;

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
<?php

namespace App\Filament\Admin\Resources\DocumentWorkflows\RelationManagers;

use App\Features\Roles\Models\Role;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class DocumentWorkflowStepsRelationManager extends RelationManager
{
    protected static string $relationship = 'steps';

    protected static ?string $title = 'Document Workflow Stages';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('step_name')
                ->label('Stage Name')
                ->placeholder('e.g. Dean Approval')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('assigned_role_id')
                ->label('Assigned Role')
                ->options(Role::orderBy('name')->pluck('name', 'id'))
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('action_label')
                ->label('Action Button Label')
                ->placeholder('e.g. Approve / Endorse')
                ->default('Approve')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('approve_status')
                ->label('Approved Status')
                ->placeholder('e.g. approved / dean_signed')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('reject_status')
                ->label('Rejected Status')
                ->placeholder('e.g. rejected / dean_returned')
                ->required()
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('step_order')
            ->defaultSort('step_order')
            ->columns([
                Tables\Columns\TextColumn::make('step_order')
                    ->label('#')
                    ->width(40)
                    ->sortable(),

                Tables\Columns\TextColumn::make('step_name')
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
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['step_order'] = $this->getOwnerRecord()->steps()->count() + 1;
                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

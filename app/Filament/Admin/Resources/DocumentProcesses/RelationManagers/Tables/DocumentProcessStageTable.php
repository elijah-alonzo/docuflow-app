<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\RelationManagers\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentProcessStagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('stage_order')
            ->defaultSort('stage_order')
            ->description('Configure the stages for the document processing.')
            ->columns([
                TextColumn::make('stage_order')
                    ->label('#')
                    ->width(40)
                    ->sortable(),

                TextColumn::make('stage_name')
                    ->label('Stage Name')
                    ->searchable(),

                TextColumn::make('role.name')
                    ->label('Assigned Role'),

                TextColumn::make('action_label')
                    ->label('Action Label'),

                TextColumn::make('approve_status')
                    ->label('Approved Status')
                    ->badge()
                    ->color('success'),

                TextColumn::make('reject_status')
                    ->label('Rejected Status')
                    ->badge()
                    ->color('danger'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Stage')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data, $livewire): array {
                        $data['stage_order'] = $livewire->getOwnerRecord()->stages()->count() + 1;

                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
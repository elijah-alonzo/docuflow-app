<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentProcessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Document Processes')
            ->description('Processes that documents undergo for review and approval.')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50),
                TextColumn::make('stages_count')
                    ->label('Stages')
                    ->counts('stages')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('All Submissions')
            ->description('Track all document submissions and their current status.')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('documentType.name')
                    ->label('Type')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('submittedBy.full_name')
                    ->label('Assigned Uploader')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('currentStep.step_name')
                    ->label('Current Stage')
                    ->default('Completed')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'success'),
                TextColumn::make('created_at')
                    ->label('Date Initiated')
                    ->dateTime()
                    ->sortable(),
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

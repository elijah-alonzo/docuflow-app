<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Tables;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Document Submission Intances')
            ->description('Overview of all document submission instances created.')
            ->columns([
                TextColumn::make('documentCategory.name')
                    ->label('Document Category')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('createdBy.full_name')
                    ->label('Creator')
                    ->sortable(),
                TextColumn::make('uploaders.full_name')
                    ->label('Uploader')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->placeholder('Unassigned'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('currentProcessStage.stage_name')
                    ->label('Current Stage')
                    ->default('Completed')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'success'),
                TextColumn::make('created_at')
                    ->label('Date Created')
                    ->date()
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
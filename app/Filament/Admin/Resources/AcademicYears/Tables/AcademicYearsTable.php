<?php

namespace App\Filament\Admin\Resources\AcademicYears\Tables;

use App\Models\AcademicYear;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AcademicYearsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Academic Years')
            ->description('Manage academic year records and their current status.')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('year')
                    ->label('Academic Year')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => $state === AcademicYear::STATUS_CURRENT ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->color('info'),
                    DeleteAction::make(),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}

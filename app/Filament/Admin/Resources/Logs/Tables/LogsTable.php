<?php

namespace App\Filament\Admin\Resources\Logs\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('System Logs')
            ->description('Audit trail of user actions and system changes.')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'changed_password' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', $state)),
                TextColumn::make('model_type')
                    ->label('Model')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : 'System')
                    ->toggleable(),
                TextColumn::make('model_id')
                    ->label('Record ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('System')
                    ->toggleable(),
                TextColumn::make('description')
                    ->limit(60)
                    ->toggleable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('changes')
                    ->label('Changes')
                    ->formatStateUsing(fn ($state): string => $state ? json_encode($state) : '')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ActionGroup::make([
                    Action::make('view_changes')
                        ->label('View Changes')
                        ->icon('heroicon-m-eye')
                        ->modalHeading('Changes')
                        ->modalCancelActionLabel('Close')
                        ->modalSubmitAction(false)
                        ->modalContent(fn ($record) => view('app.system-logs.changes', [
                            'record' => $record,
                            'changes' => $record->changes,
                        ]))
                        ->action(fn () => null),
                ]),
            ])
            ->bulkActions([]);
    }
}

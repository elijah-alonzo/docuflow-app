<?php

namespace App\Filament\Admin\Resources\EndorsedGradingSheets\Tables;

use App\Filament\Admin\Resources\GradingSheetApprovals\Tables\GradingSheetApprovalsTable;
use App\Models\Load;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EndorsedGradingSheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Endorsed Grading Sheets')
            ->description('Review grading sheets awaiting verification.')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('user.name')
                    ->label('Faculty')
                    ->getStateUsing(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned')
                    ->searchable(),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable(),
                TextColumn::make('academicYear.year')
                    ->label('Academic Year')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('term')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('grading_sheet_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'success',
                        'to_verify' => 'warning',
                        'to_endorse' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->label('Uploaded At')
                    ->dateTime(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('verify')
                        ->label('Verify')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Load $record): bool => self::canVerify() && $record->grading_sheet_status === 'to_verify')
                        ->action(function (Load $record): void {
                            $record->update([
                                'grading_sheet_status' => 'submitted',
                            ]);

                            GradingSheetApprovalsTable::notifyStatusChange($record, 'verified');
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Load $record): bool => self::canVerify() && $record->grading_sheet_status === 'to_verify')
                        ->action(function (Load $record): void {
                            $record->update([
                                'grading_sheet_status' => 'pending',
                            ]);

                            GradingSheetApprovalsTable::notifyStatusChange($record, 'rejected');
                        }),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }

    protected static function canVerify(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->can('Update:EndorsedGradingSheet');
    }
}

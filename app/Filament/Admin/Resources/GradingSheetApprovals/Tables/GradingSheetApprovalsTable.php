<?php

namespace App\Filament\Admin\Resources\GradingSheetApprovals\Tables;

use App\Models\Load;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradingSheetApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Grading Sheet Submissions')
            ->description('Review grading sheets and move them through endorsement and verification.')
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
                    Action::make('endorse')
                        ->label('Endorse')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->visible(fn (Load $record): bool => self::canEndorse() && $record->grading_sheet_status === 'to_endorse')
                        ->action(function (Load $record): void {
                            $record->update([
                                'grading_sheet_status' => 'to_verify',
                            ]);

                            self::notifyStatusChange($record, 'endorsed');
                        }),
                    Action::make('disapprove')
                        ->label('Disapprove')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Load $record): bool => self::canEndorse() && $record->grading_sheet_status === 'to_endorse')
                        ->action(function (Load $record): void {
                            $record->update([
                                'grading_sheet_status' => 'pending',
                            ]);

                            self::notifyStatusChange($record, 'disapproved');
                        }),
                    Action::make('verify')
                        ->label('Verify')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Load $record): bool => self::canVerify() && $record->grading_sheet_status === 'to_verify')
                        ->action(function (Load $record): void {
                            $record->update([
                                'grading_sheet_status' => 'submitted',
                            ]);

                            self::notifyStatusChange($record, 'verified');
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

                            self::notifyStatusChange($record, 'rejected');
                        }),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->visible(fn (Load $record): bool => $record->grading_sheet_status === 'submitted' && filled($record->grading_sheet))
                        ->action(fn (Load $record) => self::downloadGradingSheet($record)),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }

    protected static function downloadGradingSheet(Load $record)
    {
        if (! $record->grading_sheet) {
            return null;
        }

        return \Storage::disk('public')->download(
            $record->grading_sheet,
            basename($record->grading_sheet)
        );
    }

    public static function notifyStatusChange(Load $record, string $status): void
    {
        $recipients = User::role(['Admin', 'Dean', 'Staff', 'Registrar'])->get();

        if ($record->user) {
            $recipients->push($record->user);
        }

        $recipients = $recipients->unique('id')->values();

        if ($recipients->isEmpty()) {
            return;
        }

        $statusLabel = match ($status) {
            'verified' => 'verified',
            'endorsed' => 'endorsed',
            'disapproved' => 'disapproved',
            default => 'rejected',
        };
        $facultyName = $record->user?->full_name ?? 'A faculty member';

        Notification::make()
            ->title("Grading sheet {$statusLabel}")
            ->body("{$facultyName}'s grading sheet was {$statusLabel}.")
            ->sendToDatabase($recipients);
    }

    protected static function canEndorse(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->can('Update:GradingSheetApproval')
            && ($user->hasRole('Staff') || $user->hasRole('Admin'));
    }

    protected static function canVerify(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->can('Update:GradingSheetApproval')
            && ($user->hasRole('Registrar') || $user->hasRole('Admin'));
    }
}

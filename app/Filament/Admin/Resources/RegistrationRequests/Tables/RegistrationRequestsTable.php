<?php

namespace App\Filament\Admin\Resources\RegistrationRequests\Tables;

use App\Models\RegistrationRequest;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RegistrationRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Registration Requests')
            ->description('Review new registration requests and approve access.')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('full_name')
                    ->label('Applicant')
                    ->getStateUsing(fn (RegistrationRequest $record): string => $record->full_name)
                    ->searchable(query: function ($query, string $search): void {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_initial', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    }),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->searchable(),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (RegistrationRequest $record): bool => $record->status === 'pending')
                        ->action(fn (RegistrationRequest $record) => self::approve($record)),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (RegistrationRequest $record): bool => $record->status === 'pending')
                        ->action(fn (RegistrationRequest $record) => self::reject($record)),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }

    protected static function approve(RegistrationRequest $record): void
    {
        if ($record->status !== 'pending') {
            return;
        }

        $user = User::query()->where('email', $record->email)->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $record->first_name,
                'middle_initial' => $record->middle_initial,
                'last_name' => $record->last_name,
                'email' => $record->email,
                'contact_number' => $record->contact_number,
                'program_id' => $record->program_id,
                'password' => $record->password,
            ]);
        }

        $facultyRole = Role::query()->where('name', 'Faculty')->first();

        if ($facultyRole) {
            $user->syncRoles([$facultyRole]);
        }

        $record->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejected_at' => null,
        ]);
    }

    protected static function reject(RegistrationRequest $record): void
    {
        if ($record->status !== 'pending') {
            return;
        }

        $record->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => null,
            'rejected_at' => now(),
        ]);
    }
}

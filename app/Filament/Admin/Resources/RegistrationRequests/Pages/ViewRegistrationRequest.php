<?php

namespace App\Filament\Admin\Resources\RegistrationRequests\Pages;

use App\Filament\Admin\Resources\RegistrationRequests\RegistrationRequestsResource;
use App\Models\RegistrationRequest;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class ViewRegistrationRequest extends ViewRecord
{
    protected static string $resource = RegistrationRequestsResource::class;

    protected ?string $subheading = 'Review the registration request details.';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(fn () => $this->approveRequest()),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(fn () => $this->rejectRequest()),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Applicant Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('name')
                            ->label('Full Name')
                            ->content(fn (RegistrationRequest $record): string => $record->full_name),
                        Placeholder::make('email')
                            ->label('Email')
                            ->content(fn (RegistrationRequest $record): string => $record->email),
                        Placeholder::make('contact_number')
                            ->label('Contact Number')
                            ->content(fn (RegistrationRequest $record): string => $record->contact_number),
                        Placeholder::make('program')
                            ->label('Program')
                            ->content(fn (RegistrationRequest $record): string => $record->program?->name ?? 'N/A'),
                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn (RegistrationRequest $record): string => $record->status),
                        Placeholder::make('submitted_at')
                            ->label('Submitted At')
                            ->content(fn (RegistrationRequest $record): string => $record->created_at?->format('M d, Y g:i A') ?? 'N/A'),
                    ]),
            ]);
    }

    protected function approveRequest(): void
    {
        if ($this->record->status !== 'pending') {
            return;
        }

        $user = User::query()->where('email', $this->record->email)->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $this->record->first_name,
                'middle_initial' => $this->record->middle_initial,
                'last_name' => $this->record->last_name,
                'email' => $this->record->email,
                'contact_number' => $this->record->contact_number,
                'program_id' => $this->record->program_id,
                'password' => $this->record->password,
            ]);
        }

        $facultyRole = Role::query()->where('name', 'Faculty')->first();

        if ($facultyRole) {
            $user->syncRoles([$facultyRole]);
        }

        $this->record->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejected_at' => null,
        ]);

        $this->refresh();
    }

    protected function rejectRequest(): void
    {
        if ($this->record->status !== 'pending') {
            return;
        }

        $this->record->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => null,
            'rejected_at' => now(),
        ]);

        $this->refresh();
    }
}

<?php

namespace App\Filament\App\Pages\Auth;

use App\Models\Program;
use App\Models\RegistrationRequest;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFirstNameFormComponent(),
                $this->getMiddleInitialFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getContactNumberFormComponent(),
                $this->getProgramFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        if ($this->isRegisterRateLimited($this->data['email'] ?? '')) {
            return null;
        }

        $this->wrapInDatabaseTransaction(function (): void {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $request = RegistrationRequest::create($data);

            $this->notifyRegistrationRequest($request);

            $this->callHook('afterRegister');
        });

        Notification::make()
            ->title('Registration submitted')
            ->body('Your request is pending approval. You will be able to log in once approved.')
            ->success()
            ->send();

        $this->redirect(filament()->getLoginUrl());

        return null;
    }

    protected function notifyRegistrationRequest(RegistrationRequest $request): void
    {
        $recipients = User::role(['Dean', 'Staff', 'Registrar'])->get();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('New registration request')
            ->body("{$request->full_name} submitted a registration request.")
            ->sendToDatabase($recipients);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['middle_initial'] = $this->normalizeMiddleInitial($data['middle_initial'] ?? null);
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 'pending';

        return $data;
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('First Name')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getMiddleInitialFormComponent(): Component
    {
        return TextInput::make('middle_initial')
            ->label('Middle Initial')
            ->maxLength(1)
            ->dehydrateStateUsing(fn (?string $state): ?string => $this->normalizeMiddleInitial($state));
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label('Last Name')
            ->required()
            ->maxLength(255);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique(User::class, 'email')
            ->unique(RegistrationRequest::class, 'email');
    }

    protected function getContactNumberFormComponent(): Component
    {
        return TextInput::make('contact_number')
            ->label('Contact Number')
            ->tel()
            ->required()
            ->maxLength(30)
            ->unique(User::class, 'contact_number')
            ->unique(RegistrationRequest::class, 'contact_number');
    }

    protected function getProgramFormComponent(): Component
    {
        return Select::make('program_id')
            ->label('Program')
            ->options(fn (): array => Program::query()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all())
            ->searchable()
            ->preload()
            ->required()
            ->placeholder('Select program');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->showAllValidationMessages()
            ->same('passwordConfirmation');
    }

    protected function normalizeMiddleInitial(?string $value): ?string
    {
        $value = $value !== null ? trim($value) : null;

        if ($value === null || $value === '') {
            return null;
        }

        return strtoupper($value[0]);
    }
}

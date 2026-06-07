<?php

namespace App\Filament\App\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Email or Contact Number')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $value = trim((string) ($data['login'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $value, 'password' => $password];
        }

        return ['contact_number' => $value, 'password' => $password];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}

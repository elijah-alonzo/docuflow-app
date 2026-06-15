<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Features\Roles\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->columnSpanFull()
                    ->description('These are the details for the user account.')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->columnSpanFull(),

                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->prefixIcon('heroicon-m-user')
                            ->placeholder('Enter first name'),

                        TextInput::make('middle_initial')
                            ->label('Middle Initial')
                            ->maxLength(1)
                            ->prefixIcon('heroicon-m-user')
                            ->placeholder('Enter middle initial'),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->prefixIcon('heroicon-m-user')
                            ->placeholder('Enter last name'),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->prefixIcon('heroicon-m-envelope')
                            ->placeholder('Enter email address')
                            ->columnSpan(3),

                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->required()
                            ->tel()
                            ->prefixIcon('heroicon-m-phone')
                            ->placeholder('Enter contact number')
                            ->columnSpan(3),

                        Select::make('role')
                            ->label('Role')
                            ->required()
                            ->options(fn (): array => Role::query()
                                ->where('name', '!=', 'Admin')
                                ->orderBy('name')
                                ->pluck('name', 'name')
                                ->all())
                            ->prefixIcon('heroicon-m-shield-check')
                            ->required()
                            ->default(fn (): ?string => Role::query()->where('name', '!=', 'Admin')->orderBy('name')->value('name'))
                            ->live()
                            ->afterStateHydrated(function ($set, $record): void {
                                $role = $record?->roles?->pluck('name')->first();

                                if ($role) {
                                    $set('role', $role);
                                }
                            })
                            ->dehydrated()
                            ->columnSpan(3),

                        TextInput::make('password')
                            ->password()
                            ->default('password')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->prefixIcon('heroicon-m-key')
                            ->placeholder('Enter password')
                            ->columnSpan(3),
                    ]),
            ]);
    }
}

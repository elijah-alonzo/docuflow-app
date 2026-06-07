<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarFormComponent(),
                $this->getFirstNameFormComponent(),
                $this->getMiddleInitialFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getContactNumberFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function getAvatarFormComponent(): Component
    {
        return FileUpload::make('avatar')
            ->label('Profile Picture')
            ->image()
            ->disk('public')
            ->directory('avatars');
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('First Name')
            ->required()
            ->maxLength(255);
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

    protected function getContactNumberFormComponent(): Component
    {
        return TextInput::make('contact_number')
            ->label('Contact Number')
            ->tel()
            ->required()
            ->maxLength(30);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['middle_initial'] = $this->normalizeMiddleInitial($data['middle_initial'] ?? null);

        return $data;
    }

    protected function normalizeMiddleInitial(?string $value): ?string
    {
        $value = $value !== null ? trim($value) : null;

        if ($value === '') {
            return null;
        }

        return strtoupper($value[0]);
    }
}

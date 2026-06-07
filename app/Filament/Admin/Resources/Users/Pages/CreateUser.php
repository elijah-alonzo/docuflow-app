<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected ?string $subheading = 'Create a new user account.';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $roleName = $data['role'] ?? null;
        unset($data['role']);

        $user = new User;

        $user->forceFill([
            ...$data,
            'email_verified_at' => now(),
        ]);

        $user->save();

        if ($roleName) {
            $role = Role::findOrCreate($roleName);
            $user->syncRoles([$role]);
        }

        return $user;
    }
}

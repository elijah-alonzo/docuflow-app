<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Users')
            ->description('Overview of the users present in the system.')
            ->defaultPaginationPageOption(50)
            ->columns([
                ImageColumn::make('avatar')
                    ->circular()
                    ->label(' ')
                    ->imageSize(40)
                    ->grow(false)
                    ->getStateUsing(function (User $record) {
                        $avatar = $record->avatar;

                        if (! $avatar) {
                            return null;
                        }

                        return str_starts_with($avatar, 'http')
                            ? $avatar
                            : asset('storage/'.$avatar);
                    })
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=0F172A&color=FFFFFF'),
                TextColumn::make('name')
                    ->label('Name')
                    ->getStateUsing(fn (User $record): string => $record->full_name)
                    ->searchable(query: function ($query, string $search): void {
                        $query->where(function ($nameQuery) use ($search): void {
                            $nameQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_initial', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function ($query, string $direction): void {
                        $query->orderBy('last_name', $direction)
                            ->orderBy('first_name', $direction);
                    }),
                TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->icon('heroicon-m-phone')
                    ->placeholder('No contact number'),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                BadgeColumn::make('role')
                    ->label('Role')
                    ->getStateUsing(fn (User $record): string => $record->roles->pluck('name')->first() ?? 'none')
                    ->color(fn (string $state): string => $state === 'none' ? 'gray' : 'warning')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('info'),
                    DeleteAction::make()
                        ->visible(fn (User $record): bool => $record->email !== 'admin@sys.com'),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}

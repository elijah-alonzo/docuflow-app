<?php

namespace App\Filament\Admin\Resources\Loads\Tables;

use App\Models\Load;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LoadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Faculty Teaching Loads')
            ->description('A list of your teaching loads of all users in the system.')
            ->defaultPaginationPageOption(50)
            ->columns([
                ColumnGroup::make('Faculty', [
                    ImageColumn::make('user.avatar')
                        ->label('Picture')
                        ->circular()
                        ->imageSize(40)
                        ->getStateUsing(function (Load $record) {
                            $name = $record->user?->full_name ?? 'Faculty';
                            $avatar = $record->user?->avatar;

                            return $avatar
                                ? (str_starts_with($avatar, 'http')
                                    ? $avatar
                                    : asset('storage/'.$avatar))
                                : 'https://ui-avatars.com/api/?name='.urlencode($name).'&background=0F172A&color=FFFFFF';
                        }),
                    TextColumn::make('user_name')
                        ->label('Faculty')
                        ->weight('medium')
                        ->placeholder('Unassigned')
                        ->getStateUsing(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned')
                        ->searchable(query: function (Builder $query, string $search) {
                            $query->whereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('middle_initial', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                        }),
                ]),
                ColumnGroup::make('Subject Information', [
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
                ]),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->color('info'),
                    DeleteAction::make()
                        ->visible(fn (Load $record): bool => auth()->user()?->can('delete', $record) ?? false),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->label('Actions'),
            ]);
    }
}

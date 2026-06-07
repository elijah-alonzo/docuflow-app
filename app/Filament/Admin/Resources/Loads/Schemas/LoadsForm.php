<?php

namespace App\Filament\Admin\Resources\Loads\Schemas;

use App\Models\AcademicYear;
use App\Models\Load;
use App\Models\Subject;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LoadsForm
{
    private static function canManageLoad(?Load $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($record) {
            return $user->can('update', $record);
        }

        return $user->can('create', Load::class);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Load Details')
                    ->columnSpanFull()
                    ->description('These are the details for the teaching load assignment.')
                    ->schema([
                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('program', 'name')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->default(fn (): ?int => Auth::user()?->hasRole('Registrar')
                                ? Auth::user()?->program_id
                                : null)
                            ->required(fn (?Load $record): bool => self::canManageLoad($record))
                            ->disabled(fn (?Load $record): bool => ! self::canManageLoad($record)
                                || Auth::user()?->hasRole('Registrar'))
                            ->live()
                            ->afterStateUpdated(function ($set): void {
                                $set('subject_id', null);
                            }),

                        Select::make('subject_id')
                            ->label('Subject')
                            ->options(fn ($get) => Subject::query()
                                ->where('program_id', $get('program_id'))
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-book-open')
                            ->required(fn (?Load $record): bool => self::canManageLoad($record))
                            ->disabled(fn ($get, ?Load $record): bool => blank($get('program_id')) || ! self::canManageLoad($record)),

                        Select::make('term')
                            ->label('Semester')
                            ->options([
                                'First Semester' => 'First Semester',
                                'Second Semester' => 'Second Semester',
                                'Third Semester' => 'Third Semester',
                                'Summer Semester' => 'Summer Semester',
                            ])
                            ->prefixIcon('heroicon-m-calendar')
                            ->required(fn (?Load $record): bool => self::canManageLoad($record))
                            ->disabled(fn (?Load $record): bool => ! self::canManageLoad($record)),

                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->options(fn (): array => AcademicYear::query()
                                ->current()
                                ->orderByDesc('year')
                                ->pluck('year', 'id')
                                ->all())
                            ->default(fn (): ?int => AcademicYear::query()
                                ->current()
                                ->orderByDesc('year')
                                ->value('id'))
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->required(fn (?Load $record): bool => self::canManageLoad($record))
                            ->disabled(fn (?Load $record): bool => ! self::canManageLoad($record)),

                        Select::make('user_id')
                            ->label('Faculty')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return User::query()
                                    ->where(function ($query) use ($search) {
                                        $query->where('first_name', 'like', "%{$search}%")
                                            ->orWhere('middle_initial', 'like', "%{$search}%")
                                            ->orWhere('last_name', 'like', "%{$search}%");
                                    })
                                    ->orderBy('last_name')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn (User $user) => [$user->id => $user->full_name])
                                    ->all();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->full_name)
                            ->prefixIcon('heroicon-m-user')
                            ->required(fn (?Load $record): bool => self::canManageLoad($record))
                            ->disabled(fn (?Load $record): bool => ! self::canManageLoad($record)),
                    ])
                    ->columns(2),
            ]);
    }
}

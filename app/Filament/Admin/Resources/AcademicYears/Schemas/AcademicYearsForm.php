<?php

namespace App\Filament\Admin\Resources\AcademicYears\Schemas;

use App\Models\AcademicYear;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcademicYearsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Academic Year')
                    ->description('Manage academic year details and status.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('year')
                            ->label('Academic Year')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->placeholder('e.g. 2025-2026'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                AcademicYear::STATUS_CURRENT => 'Current',
                                AcademicYear::STATUS_COMPLETED => 'Completed',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-m-flag'),
                    ])
                    ->columns(2),
            ]);
    }
}

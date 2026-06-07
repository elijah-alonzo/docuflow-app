<?php

namespace App\Filament\Admin\Resources\Subjects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubjectsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subject Information')
                    ->columnSpanFull()
                    ->description('These are the details and information for the subjects offered.')
                    ->schema([
                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('program', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->required(),

                        TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-tag')
                            ->placeholder('Enter subject code (e.g. GS-501)'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-bookmark-square')
                            ->placeholder('Enter subject name'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->onIcon('heroicon-m-check-circle')
                            ->offIcon('heroicon-m-x-circle'),
                    ])
                    ->columns(2),
            ]);
    }
}

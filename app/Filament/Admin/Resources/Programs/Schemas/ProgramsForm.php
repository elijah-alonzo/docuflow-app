<?php

namespace App\Filament\Admin\Resources\Programs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Program Information')
                    ->description('These are the program details and information.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-tag')
                            ->placeholder('Enter program code (e.g. MIT)'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-bookmark-square')
                            ->placeholder('Enter program name'),

                        Select::make('degree')
                            ->label('Degree')
                            ->options([
                                'Doctoral' => 'Doctoral',
                                'Masteral' => 'Masteral',
                            ])
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->required(),

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

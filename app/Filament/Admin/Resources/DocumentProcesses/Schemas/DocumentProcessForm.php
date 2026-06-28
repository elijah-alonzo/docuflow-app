<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document Process Details')
                    ->description('Configure the main process settings.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-arrow-path-rounded-square'),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
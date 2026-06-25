<?php

namespace App\Filament\Admin\Resources\DocumentTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Document Category Details')
                    ->description('Manage document category configuration.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-document-text'),
                        Select::make('workflow_id')
                            ->label('Document Workflow')
                            ->relationship('workflow', 'name')
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-arrow-path-rounded-square'),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
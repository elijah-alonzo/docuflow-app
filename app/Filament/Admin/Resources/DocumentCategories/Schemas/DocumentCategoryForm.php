<?php

namespace App\Filament\Admin\Resources\DocumentCategories\Schemas;

use App\Features\Roles\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class DocumentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Category Details')
                        ->description('Basic information about the document category.')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->prefixIcon('heroicon-o-document-text'),

                            Select::make('document_process_id')
                                ->label('Document Process')
                                ->relationship('documentProcess', 'name')
                                ->preload()
                                ->required()
                                ->prefixIcon('heroicon-o-arrow-path-rounded-square'),

                            Textarea::make('description')
                                ->maxLength(65535)
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->required(),
                        ])
                        ->columns(2),

                    Step::make('Access Control')
                        ->description('Configure who can manage and submit documents for this category.')
                        ->schema([
                            Select::make('allowed_creator_roles')
                                ->label('Manager Roles')
                                ->helperText('Users with these roles can create and manage submission instances for this category.')
                                ->multiple()
                                ->options(fn (): array => Role::query()
                                    ->where('name', '!=', 'Admin')
                                    ->orderBy('name')
                                    ->pluck('name', 'name')
                                    ->all())
                                ->required()
                                ->searchable()
                                ->preload()
                                ->prefixIcon('heroicon-o-user-group'),

                            Select::make('allowed_uploader_roles')
                                ->label('Uploader Roles')
                                ->helperText('Users with these roles can be assigned to upload documents for submission instances.')
                                ->multiple()
                                ->options(fn (): array => Role::query()
                                    ->where('name', '!=', 'Admin')
                                    ->orderBy('name')
                                    ->pluck('name', 'name')
                                    ->all())
                                ->required()
                                ->searchable()
                                ->preload()
                                ->prefixIcon('heroicon-o-arrow-up-tray'),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),
            ]);
    }
}
<?php

namespace App\Filament\Admin\Resources\DocumentCategories\RelationManagers;

use App\Features\DocumentCategoryFields\Models\DocumentCategoryField;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager as BaseRelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DocumentCategoryFieldsRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'fields';

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Document Category Fields';

public function form(Schema $schema): Schema
{
    return $schema
        ->columns(2)
        ->components([
            TextInput::make('label')
                ->label('Field Label')
                ->placeholder('e.g. Research Title')
                ->required()
                ->maxLength(255)
                ->prefixIcon('heroicon-o-tag')
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                    if ($operation !== 'create' || blank($state)) {
                        return;
                    }

                    $set('field_key', Str::snake(Str::ascii($state)));
                })
                ->columnSpanFull(),

            TextInput::make('field_key')
                ->label('Field Key')
                ->placeholder('e.g. research_title')
                ->helperText('Stable data identifier. Spaces and uppercase is prohibitted.')
                ->required()
                ->alphaDash()
                ->prefixIcon('heroicon-o-key')
                ->maxLength(255),

            Select::make('type')
                ->label('Field Type')
                ->options(DocumentCategoryField::TYPES)
                ->default('text')
                ->required()
                ->prefixIcon('heroicon-o-list-bullet')
                ->live(),

            TextInput::make('help_text')
                ->label('Help Text')
                ->placeholder('Optional guidance shown to users')
                ->prefixIcon('heroicon-o-information-circle')
                ->maxLength(255)
                ->columnSpanFull(),

            KeyValue::make('options')
                ->label('Dropdown Choices')
                ->keyLabel('Value (stored)')
                ->valueLabel('Label (shown)')
                ->reorderable()
                ->visible(fn (callable $get): bool => $get('type') === 'select')
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->prefixIcon('heroicon-o-bars-3-bottom-left')
                ->default(0),

            Toggle::make('is_required')
                ->label('Required')
                ->default(false),
        ]);
}

    public function table(Table $table): Table
    {
        return $table
            ->description('Configure the fields that users inputs when creating documents.')
            ->recordTitleAttribute('label')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('field_key')->badge()->color('gray'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => DocumentCategoryField::TYPES[$state] ?? $state),
                IconColumn::make('is_required')->label('Required')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Field')
                    ->createAnother(false),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}

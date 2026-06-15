<?php

namespace App\Filament\Admin\Resources\DocumentTypes\Relations;

use App\Features\DocumentTypeFields\Models\DocumentTypeField;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager as BaseRelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RelationManager extends BaseRelationManager
{
    protected static string $relationship = 'fields';

    protected static ?string $title = 'Submission Fields';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('label')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                        if ($operation !== 'create' || blank($state)) {
                            return;
                        }
                        $set('field_key', Str::snake(Str::ascii($state)));
                    }),

                TextInput::make('field_key')
                    ->label('Field Key')
                    ->helperText('Stable identifier used in the metadata JSON. Lowercase, no spaces.')
                    ->required()
                    ->alphaDash()
                    ->maxLength(255),

                Select::make('type')
                    ->options(DocumentTypeField::TYPES)
                    ->default('text')
                    ->required()
                    ->live(),

                TextInput::make('help_text')
                    ->label('Help Text')
                    ->maxLength(255),

                KeyValue::make('options')
                    ->label('Dropdown Choices')
                    ->keyLabel('Value (stored)')
                    ->valueLabel('Label (shown)')
                    ->reorderable()
                    ->visible(fn (callable $get): bool => $get('type') === 'select')
                    ->columnSpanFull(),

                Toggle::make('is_required')
                    ->label('Required')
                    ->default(false),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('field_key')->badge()->color('gray'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => DocumentTypeField::TYPES[$state] ?? $state),
                IconColumn::make('is_required')->label('Required')->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}

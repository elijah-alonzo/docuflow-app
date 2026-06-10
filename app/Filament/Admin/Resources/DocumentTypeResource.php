<?php

namespace App\Filament\Admin\Resources;

use App\Models\DocumentType;
use App\Features\Workflow\Models\Workflow;
use App\Filament\Admin\Resources\DocumentTypeResource\Pages\CreateDocumentType;
use App\Filament\Admin\Resources\DocumentTypeResource\Pages\EditDocumentType;
use App\Filament\Admin\Resources\DocumentTypeResource\Pages\ListDocumentTypes;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DocumentTypeResource extends Resource
{
    protected static ?string $model = DocumentType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static UnitEnum|string|null $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Document Categories';

    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('workflow_id')
                    ->label('Workflow Template')
                    ->relationship('workflow', 'name')
                    ->preload()
                    ->required(),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('workflow.name')
                    ->label('Workflow Template')
                    ->badge()
                    ->color('primary'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentTypes::route('/'),
            'create' => CreateDocumentType::route('/create'),
            'edit' => EditDocumentType::route('/{record}/edit'),
        ];
    }
}

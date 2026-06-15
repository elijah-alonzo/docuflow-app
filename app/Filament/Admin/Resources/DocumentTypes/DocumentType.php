<?php

namespace App\Filament\Admin\Resources\DocumentTypes;

use App\Filament\Admin\Resources\DocumentTypes\Pages\CreateDocumentType;
use App\Filament\Admin\Resources\DocumentTypes\Pages\EditDocumentType;
use App\Filament\Admin\Resources\DocumentTypes\Pages\ListDocumentTypes;
use App\Filament\Admin\Resources\DocumentTypes\Relations\RelationManager as FieldsRelationManager;
use App\Features\DocumentTypes\Models\DocumentType as DocumentTypeModel;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DocumentType extends Resource
{
    protected static ?string $model = DocumentTypeModel::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static UnitEnum|string|null $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Document Categories';

    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
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
                            ->label('Workflow Template')
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
                    ->columns(2),
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
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FieldsRelationManager::class,
        ];
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

<?php

namespace App\Filament\Admin\Resources\DocumentTypes;

use App\Filament\Admin\Resources\DocumentTypes\Pages\CreateDocumentType;
use App\Filament\Admin\Resources\DocumentTypes\Pages\EditDocumentType;
use App\Filament\Admin\Resources\DocumentTypes\Pages\ListDocumentTypes;
use App\Filament\Admin\Resources\DocumentTypes\Relations\RelationManager as FieldsRelationManager;
use App\Filament\Admin\Resources\DocumentTypes\Schemas\DocumentTypeForm;
use App\Filament\Admin\Resources\DocumentTypes\Tables\DocumentTypesTable;
use App\Features\DocumentTypes\Models\DocumentType as DocumentTypeModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
        return DocumentTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentTypesTable::configure($table);
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
<?php

namespace App\Filament\Admin\Resources\DocumentCategories;

use App\Filament\Admin\Resources\DocumentCategories\Pages\CreateDocumentCategory;
use App\Filament\Admin\Resources\DocumentCategories\Pages\EditDocumentCategory;
use App\Filament\Admin\Resources\DocumentCategories\Pages\ListDocumentCategories;
use App\Filament\Admin\Resources\DocumentCategories\RelationManagers\DocumentCategoryFields;
use App\Filament\Admin\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use App\Filament\Admin\Resources\DocumentCategories\Tables\DocumentCategoriesTable;
use App\Features\DocumentCategories\Models\DocumentCategory as DocumentCategoryModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategoryModel::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Document Categories';

    protected static ?int $navigationSort = 31;

    public static function getModelLabel(): string
    {
        return 'Document Category';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Document Categories';
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DocumentCategoryFields::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentCategories::route('/'),
            'create' => CreateDocumentCategory::route('/create'),
            'edit' => EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}

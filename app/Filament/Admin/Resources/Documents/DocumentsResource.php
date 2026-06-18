<?php

namespace App\Filament\Admin\Resources\Documents;

use App\Features\Documents\Models\Document;
use App\Filament\Admin\Resources\Documents\Pages\CreateDocument;
use App\Filament\Admin\Resources\Documents\Pages\EditDocument;
use App\Filament\Admin\Resources\Documents\Pages\ListDocuments;
use App\Filament\Admin\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Admin\Resources\Documents\Tables\DocumentsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DocumentsResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Document Submissions';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}

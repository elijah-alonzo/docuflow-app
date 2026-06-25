<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Filament\Admin\Resources\DocumentSubmissions\Pages\CreateDocumentSubmission;
use App\Filament\Admin\Resources\DocumentSubmissions\Pages\EditDocumentSubmission;
use App\Filament\Admin\Resources\DocumentSubmissions\Pages\ListDocumentSubmissions;
use App\Filament\Admin\Resources\DocumentSubmissions\Schemas\DocumentSubmissionForm;
use App\Filament\Admin\Resources\DocumentSubmissions\Tables\DocumentSubmissionsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DocumentSubmissionResource extends Resource
{
    protected static ?string $model = DocumentSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Document Submissions';

    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return 'Document Submission';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Document Submissions';
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentSubmissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentSubmissions::route('/'),
            'create' => CreateDocumentSubmission::route('/create'),
            'edit' => EditDocumentSubmission::route('/{record}/edit'),
        ];
    }
}

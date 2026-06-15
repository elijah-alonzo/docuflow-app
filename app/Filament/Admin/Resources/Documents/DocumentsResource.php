<?php

namespace App\Filament\Admin\Resources\Documents;

use App\Features\Documents\Models\Document;
use App\Features\DocumentTypes\Models\DocumentType;
use App\Filament\Admin\Resources\Documents\Pages\CreateDocument;
use App\Filament\Admin\Resources\Documents\Pages\EditDocument;
use App\Filament\Admin\Resources\Documents\Pages\ListDocuments;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
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
        return $schema
            ->components([
                Section::make('Document Submission')
                    ->description('Submit a document for routing and approval.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-pencil-square'),
                        Select::make('document_type_id')
                            ->label('Document Type')
                            ->options(fn () => DocumentType::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-o-document-duplicate'),
                        FileUpload::make('file_path')
                            ->label('Document File')
                            ->required()
                            ->disk('public')
                            ->directory('documents')
                            ->columnSpanFull(),
                        Grid::make()
                            ->schema(function (callable $get) {
                                $documentTypeId = $get('document_type_id');
                                if (!$documentTypeId) {
                                    return [];
                                }

                                $documentType = DocumentType::find($documentTypeId);
                                if (!$documentType) {
                                    return [];
                                }

                                $fields = $documentType->fields;
                                $components = [];

                                foreach ($fields as $field) {
                                    $component = match ($field->type) {
                                        'textarea' => Textarea::make("metadata.{$field->field_key}"),
                                        'number' => TextInput::make("metadata.{$field->field_key}")->numeric()->prefixIcon('heroicon-o-hashtag'),
                                        'date' => DatePicker::make("metadata.{$field->field_key}")->prefixIcon('heroicon-o-calendar'),
                                        'select' => Select::make("metadata.{$field->field_key}")
                                            ->options($field->options ?? [])
                                            ->prefixIcon('heroicon-o-list-bullet'),
                                        'checkbox' => Toggle::make("metadata.{$field->field_key}"),
                                        default => TextInput::make("metadata.{$field->field_key}")->prefixIcon('heroicon-o-tag'),
                                    };

                                    $component
                                        ->label($field->label)
                                        ->helperText($field->help_text)
                                        ->required($field->is_required);

                                    $components[] = $component;
                                }

                                return $components;
                            })
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                View::make('Admin.DocumentTimeline.holder')
                    ->visible(fn ($record): bool => $record !== null)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('documentType.name')
                    ->label('Type')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('submittedBy.full_name')
                    ->label('Submitted By')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('currentStep.step_name')
                    ->label('Current Stage')
                    ->default('Completed')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'success'),
                TextColumn::make('created_at')
                    ->label('Date Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
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

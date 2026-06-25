<?php

namespace App\Filament\Admin\Resources\DocumentSubmissions\Schemas;

use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\Users\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('New Document Submission')
                    ->description('Initiate a document submission, select the workflow, and assign an uploader.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-pencil-square'),
                        Select::make('document_category_id')
                            ->label('Document Category')
                            ->options(fn () => DocumentCategory::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-o-document-duplicate')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $documentCategory = DocumentCategory::find($state);
                                    if ($documentCategory && $documentCategory->document_workflow_id) {
                                        $set('document_workflow_id', $documentCategory->document_workflow_id);
                                    }
                                }
                            }),
                        Select::make('document_workflow_id')
                            ->label('Document Workflow')
                            ->relationship('documentWorkflow', 'name')
                            ->required()
                            ->live()
                            ->preload()
                            ->prefixIcon('heroicon-o-arrow-path-rounded-square'),
                        Select::make('submitted_by')
                            ->label('Assigned Uploader')
                            ->relationship('submittedBy')
                            ->getOptionLabelFromRecordUsing(fn (User $record) => $record->full_name)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-user'),
                        FileUpload::make('file_path')
                            ->label('Document File')
                            ->disk('public')
                            ->directory('documents')
                            ->columnSpanFull(),
                        Grid::make()
                            ->schema(function (callable $get) {
                                $documentCategoryId = $get('document_category_id');
                                if (!$documentCategoryId) {
                                    return [];
                                }

                                $documentCategory = DocumentCategory::find($documentCategoryId);
                                if (!$documentCategory) {
                                    return [];
                                }

                                $fields = $documentCategory->fields;
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
                            ->columns(1)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

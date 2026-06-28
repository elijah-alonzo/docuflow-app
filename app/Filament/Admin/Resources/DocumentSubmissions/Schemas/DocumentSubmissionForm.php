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
                    ->description('Initiate a document submission, select the process, and assign uploaders.')
                    ->schema([
                        Select::make('document_category_id')
                            ->label('Document Category')
                            ->options(fn () => DocumentCategory::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-o-document-duplicate')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $documentCategory = DocumentCategory::find($state);
                                    if ($documentCategory && $documentCategory->document_process_id) {
                                        $set('document_process_id', $documentCategory->document_process_id);
                                    }
                                }
                            }),
                        Select::make('uploaders')
                            ->label('Assigned Uploaders')
                            ->relationship('uploaders', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (User $record) => $record->full_name)
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-user-group'),
                        FileUpload::make('file_path')
                            ->label('Document File')
                            ->disk('public')
                            ->directory('documents'),
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
                    ])
                    ->columnSpanfull()
            ]);
    }
}
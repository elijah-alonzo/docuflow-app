<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use App\Features\DocumentTypes\Models\DocumentType;
use App\Features\Users\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class DocumentForm
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
                        Select::make('document_type_id')
                            ->label('Document Type')
                            ->options(fn () => DocumentType::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-o-document-duplicate')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $documentType = DocumentType::find($state);
                                    if ($documentType && $documentType->workflow_id) {
                                        $set('workflow_id', $documentType->workflow_id);
                                    }
                                }
                            }),
                        Select::make('workflow_id')
                            ->label('Workflow Template')
                            ->relationship('workflow', 'name')
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
                            ->columns(1)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

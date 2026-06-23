<?php

namespace App\Filament\Admin\Resources\Approvals\Pages;

use App\Filament\Admin\Resources\Approvals\DocumentApprovalResource;
use App\Features\Documents\Models\Document;
use App\Features\Workflows\Services\WorkflowEngine;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ViewDocumentApproval extends ViewRecord
{
    protected static string $resource = DocumentApprovalResource::class;

    protected function getHeaderActions(): array
    {
        /** @var WorkflowEngine $engine */
        $engine = app(WorkflowEngine::class);

        return [
            Action::make('approve')
                ->label(fn () => $this->record->currentStep?->action_label ?: 'Approve')
                ->color('success')
                ->visible(fn (): bool => in_array('approve', $engine->getAvailableActions($this->record, auth()->user())))
                ->form([
                    Textarea::make('remarks')
                        ->label('Approval Remarks / Comments')
                        ->placeholder('Enter optional comments for this stage approval...')
                        ->rows(3),
                ])
                ->action(function (array $data) use ($engine): void {
                    $engine->approve($this->record, auth()->user(), $data['remarks'] ?? null);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->visible(fn (): bool => in_array('reject', $engine->getAvailableActions($this->record, auth()->user())))
                ->form([
                    Textarea::make('remarks')
                        ->label('Reason for Rejection')
                        ->placeholder('Please explain the reason for rejecting or returning this document...')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function (array $data) use ($engine): void {
                    $engine->reject($this->record, auth()->user(), $data['remarks'] ?? null);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),

            Action::make('download')
                ->label('Download')
                ->color('gray')
                ->visible(fn (): bool => filled($this->record->file_path))
                ->action(fn () => $this->downloadDocument()),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                View::make('admin.document_approval.preview')
                    ->columnSpan(2)
                    ->viewData(fn (Document $record): array => [
                        'previewHtml' => $this->renderPreview($record),
                    ]),

                Section::make('Document Metadata')
                    ->schema(function (Document $record) {
                        $documentType = $record->documentType;
                        if (!$documentType) {
                            return [];
                        }

                        $fields = $documentType->fields;
                        $components = [];

                        foreach ($fields as $field) {
                            $component = match ($field->type) {
                                'textarea' => Textarea::make("metadata.{$field->field_key}"),
                                'number' => TextInput::make("metadata.{$field->field_key}")->numeric(),
                                'date' => DatePicker::make("metadata.{$field->field_key}"),
                                'select' => Select::make("metadata.{$field->field_key}")
                                    ->options($field->options ?? []),
                                'checkbox' => Toggle::make("metadata.{$field->field_key}"),
                                default => TextInput::make("metadata.{$field->field_key}"),
                            };

                            $component
                                ->label($field->label)
                                ->helperText($field->help_text)
                                ->disabled();

                            $components[] = $component;
                        }

                        return $components;
                    })
                    ->columns(2)
                    ->columnSpan(2)
                    ->visible(fn (Document $record) => $record->documentType && $record->documentType->fields()->exists()),
            ]);
    }

    protected function renderPreview(Document $record): HtmlString
    {
        if (!$record->file_path) {
            return new HtmlString('No file is available.');
        }

        $url = Storage::disk('public')->url($record->file_path);
        $extension = Str::lower(pathinfo($record->file_path, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return new HtmlString(
                '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" style="width:100%; height:750px; border:0; border-radius:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);" title="Document PDF Preview"></iframe>'
            );
        }

        if (in_array($extension, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
            return new HtmlString(
                '<div style="width:100%; text-align:center; padding:16px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">' .
                '<img src="'.$url.'" style="max-width:100%; max-height:700px; border-radius:8px;" alt="Preview">' .
                '</div>'
            );
        }

        return new HtmlString(
            '<div style="padding:24px; text-align:center; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">' .
            '<p style="margin-bottom:12px; color:#475569;">Preview not supported for <strong>.'.$extension.'</strong> files.</p>' .
            '<a class="fi-btn fi-btn-size-md fi-btn-color-primary" href="'.$url.'" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; gap:8px; background:#4f46e5; color:white; padding:8px 16px; border-radius:8px; font-weight:600; text-decoration:none;">Open in New Tab</a>' .
            '</div>'
        );
    }

    protected function downloadDocument()
    {
        if (!$this->record->file_path) {
            return null;
        }

        return Storage::disk('public')->download(
            $this->record->file_path,
            basename($this->record->file_path)
        );
    }
}

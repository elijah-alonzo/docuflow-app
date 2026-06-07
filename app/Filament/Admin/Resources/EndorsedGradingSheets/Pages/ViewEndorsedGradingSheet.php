<?php

namespace App\Filament\Admin\Resources\EndorsedGradingSheets\Pages;

use App\Filament\Admin\Resources\EndorsedGradingSheets\EndorsedGradingSheetsResource;
use App\Filament\Admin\Resources\GradingSheetApprovals\Tables\GradingSheetApprovalsTable;
use App\Models\Load;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ViewEndorsedGradingSheet extends ViewRecord
{
    protected static string $resource = EndorsedGradingSheetsResource::class;

    protected static ?string $navigationLabel = 'Preview';

    protected ?string $subheading = 'Review the endorsed grading sheet and verify it.';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify')
                ->label('Verify')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->canVerify() && $this->record->grading_sheet_status === 'to_verify')
                ->action(function (): void {
                    $this->record->update([
                        'grading_sheet_status' => 'submitted',
                    ]);

                    GradingSheetApprovalsTable::notifyStatusChange($this->record, 'verified');

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->canVerify() && $this->record->grading_sheet_status === 'to_verify')
                ->action(function (): void {
                    $this->record->update([
                        'grading_sheet_status' => 'pending',
                    ]);

                    GradingSheetApprovalsTable::notifyStatusChange($this->record, 'rejected');

                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                View::make('public.progress')
                    ->viewData([
                        'current' => $this->record->grading_sheet_status,
                    ])
                    ->columnSpanFull(),
                View::make('admin.view.page')
                    ->viewData([
                        'record' => $this->record,
                    ])
                    ->columnSpan(1),
                Placeholder::make('grading_sheet_preview')
                    ->label('Grading Sheet Preview')
                    ->columnSpan(2)
                    ->content(fn (Load $record): HtmlString => $this->renderPreview($record)),
            ]);
    }

    protected function renderPreview(Load $record): HtmlString
    {
        if (! $record->grading_sheet) {
            return new HtmlString('No grading sheet file is available.');
        }

        $disk = Storage::disk('public');
        $url = $disk instanceof FilesystemAdapter
            ? $disk->url($record->grading_sheet)
            : Storage::url($record->grading_sheet);
        $extension = Str::lower(pathinfo($record->grading_sheet, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return new HtmlString(
                '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" style="width:100%; height:700px; border:0;" title="Grading Sheet"></iframe>'
            );
        }

        return new HtmlString('<a href="'.$url.'" target="_blank" rel="noopener">Open grading sheet</a>');
    }

    protected function canVerify(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->can('Update:EndorsedGradingSheet');
    }
}

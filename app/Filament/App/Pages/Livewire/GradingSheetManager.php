<?php

namespace App\App\Livewire;

use App\Models\Load;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class GradingSheetManager extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?int $selectedLoadId = null;

    public function uploadAction(): Action
    {
        return Action::make('upload')
            ->label('Upload Grading Sheet')
            ->modalHeading(fn (): string => $this->getSelectedLoad()?->subject?->name ?? 'Upload Grading Sheet')
            ->modalDescription(fn (): string => $this->getSelectedLoad()?->program?->name . ' • ' . $this->getSelectedLoad()?->term)
            ->modalWidth('lg')
            ->form([
                FileUpload::make('grading_sheet')
                    ->label('Grading Sheet File')
                    ->disk('public')
                    ->directory('grading-sheets')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                    ])
                    ->maxSize(10 * 1024)
                    ->required(),
            ])
            ->fillForm(function (): array {
                $load = $this->getSelectedLoad();
                return [
                    'grading_sheet' => $load?->grading_sheet,
                ];
            })
            ->action(function (array $data): void {
                $load = $this->getSelectedLoad();

                if (! $load) return;

                $load->update([
                    'grading_sheet' => $data['grading_sheet'],
                    'grading_sheet_status' => 'to_endorse',
                ]);

                $this->notifyReviewers();

                Notification::make()
                    ->title('Grading sheet uploaded')
                    ->body('Your grading sheet has been submitted for endorsement.')
                    ->success()
                    ->send();
            });
    }

    public function reuploadAction(): Action
    {
        return Action::make('reupload')
            ->label('Re-upload Grading Sheet')
            ->modalHeading(fn (): string => $this->getSelectedLoad()?->subject?->name ?? 'Re-upload Grading Sheet')
            ->modalDescription(fn (): string => $this->getSelectedLoad()?->program?->name . ' • ' . $this->getSelectedLoad()?->term)
            ->modalWidth('lg')
            ->form([
                FileUpload::make('grading_sheet')
                    ->label('Grading Sheet File')
                    ->disk('public')
                    ->directory('grading-sheets')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                    ])
                    ->maxSize(10 * 1024)
                    ->required(),
            ])
            ->fillForm(function (): array {
                $load = $this->getSelectedLoad();
                return [
                    'grading_sheet' => $load?->grading_sheet,
                ];
            })
            ->modalSubmitActionLabel('Re-submit')
            ->action(function (array $data): void {
                $load = $this->getSelectedLoad();

                if (! $load) return;

                // Reset to pending first, then fresh cycle
                $load->update([
                    'grading_sheet' => $data['grading_sheet'],
                    'grading_sheet_status' => 'pending',
                ]);

                Notification::make()
                    ->title('Grading sheet reset')
                    ->body('Your grading sheet has been reset. Please re-upload to resubmit.')
                    ->warning()
                    ->send();
            });
    }

    public function openUpload(int $loadId): void
    {
        $this->selectedLoadId = $loadId;
        $this->mountAction('upload');
    }

    public function openReupload(int $loadId): void
    {
        $this->selectedLoadId = $loadId;
        $this->mountAction('reupload');
    }

    protected function getSelectedLoad(): ?Load
    {
        return $this->selectedLoadId
            ? Load::with(['subject', 'program', 'academicYear'])->find($this->selectedLoadId)
            : null;
    }

    protected function notifyReviewers(): void
    {
        $recipients = User::role(['Admin', 'Dean', 'Staff', 'Registrar'])->get();

        if ($recipients->isEmpty()) return;

        Notification::make()
            ->title('Grading sheet submitted')
            ->body('A grading sheet has been submitted for endorsement.')
            ->sendToDatabase($recipients);
    }

    public function render()
    {
        $loads = Load::query()
            ->where('user_id', auth()->id())
            ->with(['program', 'subject', 'academicYear'])
            ->orderByDesc('academic_year_id')
            ->orderBy('term')
            ->get();

        return view('app.actions.submission', compact('loads'));
    }
}
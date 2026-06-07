<?php

namespace App\Filament\Admin\Resources\GradingSheetApprovals\Pages;

use App\Filament\Admin\Resources\GradingSheetApprovals\GradingSheetApprovalsResource;
use App\Models\Load;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGradingSheetApprovals extends ListRecords
{
    protected static string $resource = GradingSheetApprovalsResource::class;

    protected ?string $subheading = 'Review and manage grading sheet submissions.';

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('grading_sheet_status', 'pending')),
            'endorse' => Tab::make('To Endorse')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('grading_sheet_status', 'to_endorse')),
            'verify' => Tab::make('To Verify')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('grading_sheet_status', 'to_verify')),
            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('grading_sheet_status', 'submitted')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Load::query()
            ->with(['user', 'program', 'subject', 'academicYear'])
            ->orderByDesc('updated_at');
    }
}

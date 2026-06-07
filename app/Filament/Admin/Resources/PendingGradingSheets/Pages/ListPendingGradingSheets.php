<?php

namespace App\Filament\Admin\Resources\PendingGradingSheets\Pages;

use App\Filament\Admin\Resources\PendingGradingSheets\PendingGradingSheetsResource;
use App\Models\Load;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPendingGradingSheets extends ListRecords
{
    protected static string $resource = PendingGradingSheetsResource::class;

    protected ?string $subheading = 'Review grading sheets awaiting endorsement.';

    protected function getTableQuery(): Builder
    {
        return Load::query()
            ->with(['user', 'program', 'subject', 'academicYear'])
            ->where('grading_sheet_status', 'to_endorse')
            ->orderByDesc('updated_at');
    }
}

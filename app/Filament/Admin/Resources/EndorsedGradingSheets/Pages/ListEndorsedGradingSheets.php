<?php

namespace App\Filament\Admin\Resources\EndorsedGradingSheets\Pages;

use App\Filament\Admin\Resources\EndorsedGradingSheets\EndorsedGradingSheetsResource;
use App\Models\Load;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEndorsedGradingSheets extends ListRecords
{
    protected static string $resource = EndorsedGradingSheetsResource::class;

    protected ?string $subheading = 'Review grading sheets awaiting verification.';

    protected function getTableQuery(): Builder
    {
        return Load::query()
            ->with(['user', 'program', 'subject', 'academicYear'])
            ->where('grading_sheet_status', 'to_verify')
            ->orderByDesc('updated_at');
    }
}

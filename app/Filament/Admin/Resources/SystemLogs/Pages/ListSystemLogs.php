<?php

namespace App\Filament\Admin\Resources\SystemLogs\Pages;

use App\Filament\Admin\Resources\SystemLogs\SystemLogsResource;
use Filament\Resources\Pages\ListRecords;

class ListSystemLogs extends ListRecords
{
    protected static string $resource = SystemLogsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

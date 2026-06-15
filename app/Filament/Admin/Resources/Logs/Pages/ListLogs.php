<?php

namespace App\Filament\Admin\Resources\Logs\Pages;

use App\Filament\Admin\Resources\Logs\LogsResource;
use Filament\Resources\Pages\ListRecords;

class ListLogs extends ListRecords
{
    protected static string $resource = LogsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

<?php

namespace App\Filament\Admin\Resources\Workflows\Pages;

use App\Filament\Admin\Resources\Workflows\WorkflowsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflows extends ListRecords
{
    protected static string $resource = WorkflowsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

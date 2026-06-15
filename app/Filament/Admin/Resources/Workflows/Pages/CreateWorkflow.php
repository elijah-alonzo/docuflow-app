<?php

namespace App\Filament\Admin\Resources\Workflows\Pages;

use App\Filament\Admin\Resources\Workflows\WorkflowsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflow extends CreateRecord
{
    protected static string $resource = WorkflowsResource::class;
}

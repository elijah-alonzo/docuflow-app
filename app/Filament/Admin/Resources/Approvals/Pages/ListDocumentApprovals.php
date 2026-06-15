<?php

namespace App\Filament\Admin\Resources\Approvals\Pages;

use App\Filament\Admin\Resources\Approvals\DocumentApprovalResource;
use Filament\Resources\Pages\ListRecords;

class ListDocumentApprovals extends ListRecords
{
    protected static string $resource = DocumentApprovalResource::class;
}

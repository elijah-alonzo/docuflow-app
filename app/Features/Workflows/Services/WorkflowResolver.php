<?php

namespace App\Features\Workflows\Services;

use App\Features\DocumentTypes\Models\DocumentType;
use App\Features\Workflows\Models\Workflow;

class WorkflowResolver
{
    public function resolve(DocumentType $documentType): ?Workflow
    {
        return $documentType->workflow;
    }
}

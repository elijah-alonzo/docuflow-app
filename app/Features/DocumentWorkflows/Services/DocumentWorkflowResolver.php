<?php

namespace App\Features\DocumentWorkflows\Services;

use App\Features\DocumentCategories\Models\DocumentCategory;
use App\Features\DocumentWorkflows\Models\DocumentWorkflow;

class DocumentWorkflowResolver
{
    public function resolve(DocumentCategory $documentCategory): ?DocumentWorkflow
    {
        return $documentCategory->documentWorkflow;
    }
}

<?php

namespace App\Features\DocumentWorkflows\Models;

use App\Features\DocumentCategories\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentWorkflow extends Model
{
    protected $table = 'document_workflows';

    protected $fillable = [
        'name',
        'description',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(DocumentWorkflowStep::class, 'document_workflow_id')->orderBy('step_order');
    }

    public function documentCategories(): HasMany
    {
        return $this->hasMany(DocumentCategory::class, 'document_workflow_id');
    }
}

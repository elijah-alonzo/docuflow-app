<?php

namespace App\Features\DocumentCategories\Models;

use App\Features\DocumentWorkflows\Models\DocumentWorkflow;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\DocumentCategoryFields\Models\DocumentCategoryField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';

    protected $fillable = [
        'name',
        'description',
        'document_workflow_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documentWorkflow(): BelongsTo
    {
        return $this->belongsTo(DocumentWorkflow::class, 'document_workflow_id');
    }

    public function documentSubmissions(): HasMany
    {
        return $this->hasMany(DocumentSubmission::class, 'document_category_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(DocumentCategoryField::class, 'document_category_id')->orderBy('sort_order');
    }
}

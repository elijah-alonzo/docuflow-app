<?php

namespace App\Features\DocumentCategories\Models;

use App\Features\DocumentProcesses\Models\DocumentProcess;
use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use App\Features\DocumentCategories\Models\DocumentCategoryField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';

    protected $fillable = [
        'name',
        'description',
        'document_process_id',
        'is_active',
        'allowed_creator_roles',
        'allowed_uploader_roles',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allowed_creator_roles' => 'array',
        'allowed_uploader_roles' => 'array',
    ];

    public function documentProcess(): BelongsTo
    {
        return $this->belongsTo(DocumentProcess::class, 'document_process_id');
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
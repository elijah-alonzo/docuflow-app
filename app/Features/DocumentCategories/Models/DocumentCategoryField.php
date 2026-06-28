<?php

namespace App\Features\DocumentCategories\Models;

use App\Features\DocumentCategories\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCategoryField extends Model
{
    protected $table = 'document_category_fields';

    public const TYPES = [
        'text' => 'Text',
        'textarea' => 'Long Text',
        'number' => 'Number',
        'date' => 'Date',
        'select' => 'Dropdown',
        'checkbox' => 'Checkbox',
    ];

    protected $fillable = [
        'document_category_id',
        'field_key',
        'label',
        'type',
        'options',
        'help_text',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
}

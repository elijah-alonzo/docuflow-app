<?php

namespace App\Features\DocumentTypeFields\Models;

use App\Features\DocumentTypes\Models\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTypeField extends Model
{
    public const TYPES = [
        'text' => 'Text',
        'textarea' => 'Long Text',
        'number' => 'Number',
        'date' => 'Date',
        'select' => 'Dropdown',
        'checkbox' => 'Checkbox',
    ];

    protected $fillable = [
        'document_type_id',
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

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}

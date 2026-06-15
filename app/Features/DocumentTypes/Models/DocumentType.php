<?php

namespace App\Features\DocumentTypes\Models;

use App\Features\Workflows\Models\Workflow;
use App\Features\Documents\Models\Document;
use App\Features\DocumentTypeFields\Models\DocumentTypeField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'workflow_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(DocumentTypeField::class)->orderBy('sort_order');
    }
}

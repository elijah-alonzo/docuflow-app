<?php

namespace App\Features\Workflows\Models;

use App\Features\DocumentTypes\Models\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class)->orderBy('step_order');
    }

    public function documentTypes(): HasMany
    {
        return $this->hasMany(DocumentType::class);
    }
}

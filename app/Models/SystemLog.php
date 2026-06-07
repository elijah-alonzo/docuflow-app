<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'action',
    'model_type',
    'model_id',
    'description',
    'changes',
    'ip_address',
    'user_agent',
])]
class SystemLog extends Model
{
    use Prunable;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subYear());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

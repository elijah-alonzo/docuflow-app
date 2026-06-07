<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['year', 'status'])]
class AcademicYear extends Model
{
    public const STATUS_CURRENT = 'current';

    public const STATUS_COMPLETED = 'completed';

    public function loads(): HasMany
    {
        return $this->hasMany(Load::class);
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CURRENT);
    }

    public static function current(): ?self
    {
        return static::query()->current()->orderByDesc('year')->first();
    }
}

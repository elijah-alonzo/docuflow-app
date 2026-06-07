<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'first_name',
    'middle_initial',
    'last_name',
    'email',
    'contact_number',
    'program_id',
    'password',
    'status',
    'approved_by',
    'approved_at',
    'rejected_at',
    'rejection_reason',
])]
class RegistrationRequest extends Model
{
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getFullNameAttribute(): string
    {
        $middleInitial = $this->middle_initial
            ? rtrim($this->middle_initial, '.').'.'
            : null;

        return trim(collect([
            $this->first_name,
            $middleInitial,
            $this->last_name,
        ])->filter()->implode(' '));
    }

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }
}

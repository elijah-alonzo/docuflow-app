<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'program_id',
    'subject_id',
    'academic_year_id',
    'term',
    'user_id',
    'grading_sheet',
    'grading_sheet_status',
])]
class Load extends Model
{
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSubmissionStatusAttribute(): string
    {
        $status = $this->grading_sheet_status ?? 'pending';

        return match ($status) {
            'to_verify' => 'to verify',
            'to_endorse' => 'to endorse',
            'submitted' => 'submitted',
            default => 'pending',
        };
    }
}

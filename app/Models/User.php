<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['avatar', 'first_name', 'middle_initial', 'last_name', 'email', 'contact_number', 'password', 'program_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

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

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public function loads(): HasMany
    {
        return $this->hasMany(Load::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'faculty_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

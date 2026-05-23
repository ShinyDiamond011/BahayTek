<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingSession extends Model
{
    protected $fillable = [
        'title',
        'description',
        'session_datetime',
        'registration_deadline',
        'max_participants',
        'venue',
        'status',
        'fee',
        'created_by',
    ];

    protected $casts = [
        'session_datetime'      => 'datetime',
        'registration_deadline' => 'datetime',
        'fee'                   => 'decimal:2',
        'max_participants'      => 'integer',
    ];

    public function isRegistrationOpen(): bool
    {
        if ($this->registration_deadline && now()->gt($this->registration_deadline)) {
            return false;
        }
        return in_array($this->status, ['open', 'ongoing', 'coming_soon']);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function getConfirmedCountAttribute(): int
    {
        return $this->registrations()->where('registration_status', 'confirmed')->count();
    }

    public function getSlotsAvailableAttribute(): int
    {
        return max(0, $this->max_participants - $this->confirmed_count);
    }
}

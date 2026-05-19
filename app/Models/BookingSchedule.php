<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'organization',
        'no_attendees',
        'topic',
        'description',
        'is_research_collab',
        'collab_institution',
        'collab_type',
        'status',
        'admin_notes',
        'cancelled_at',
        'booked_at',
    ];

    protected $casts = [
        'is_research_collab' => 'boolean',
        'cancelled_at'       => 'datetime',
        'booked_at'          => 'datetime',
        'no_attendees'       => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getServiceLabelAttribute(): string
    {
        return $this->schedule?->type_label ?? 'Consultation';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'badge-confirmed',
            'declined'  => 'badge-declined',
            'cancelled' => 'badge-cancelled',
            'completed' => 'badge-completed',
            default     => 'badge-pending',
        };
    }
}

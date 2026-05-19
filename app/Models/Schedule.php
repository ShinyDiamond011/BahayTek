<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'type',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(BookingSchedule::class);
    }

    public function getTimeRangeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time))
             . ' – '
             . date('g:i A', strtotime($this->end_time));
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'research_consultancy' => 'Research Consultancy',
            'product_development'  => 'Product Development',
            'general_consultancy'  => 'General Consultancy',
            default                => 'Any',
        };
    }
}

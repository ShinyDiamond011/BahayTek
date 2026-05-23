<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'type',
        'description',
        'subject_type',
        'subject_id',
        'user_id',
        'staff_id',
        'metadata',
        'ip_address',
        'logged_at',
    ];

    protected $casts = [
        'metadata'  => 'array',
        'logged_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(
        string $type,
        string $description,
        ?int $userId = null,
        ?int $staffId = null,
        array $metadata = [],
        ?string $ip = null
    ): static {
        return static::create([
            'type'        => $type,
            'description' => $description,
            'user_id'     => $userId,
            'staff_id'    => $staffId,
            'metadata'    => $metadata ?: null,
            'ip_address'  => $ip,
            'logged_at'   => now(),
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'admin_id',
        'qty_before',
        'qty_changed',
        'qty_after',
        'reason',
        'date_restocked',
        'recorded_at',
    ];

    protected $casts = [
        'qty_before'    => 'integer',
        'qty_changed'   => 'integer',
        'qty_after'     => 'integer',
        'date_restocked' => 'datetime',
        'recorded_at'   => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'admin_id');
    }
}

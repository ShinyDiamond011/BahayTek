<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'var_name',
        'description',
        'specification',
        'price_modifier',
        'stock_qty',
        'date_added',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'stock_qty'      => 'integer',
        'date_added'     => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function productDevelopments(): HasMany
    {
        return $this->hasMany(ProductDevelopment::class, 'variant_id');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) $this->product->price + (float) $this->price_modifier;
    }
}

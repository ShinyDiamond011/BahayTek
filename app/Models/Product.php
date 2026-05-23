<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'prod_name',
        'prod_description',
        'price',
        'stock_qty',
        'stock_level',
        'image_url',
        'category',
        'is_active',
        'added_at',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'stock_qty' => 'integer',
        'is_active' => 'boolean',
        'added_at'  => 'datetime',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function updateStockLevel(): void
    {
        $level = match(true) {
            $this->stock_qty === 0  => 'out_of_stock',
            $this->stock_qty <= 10  => 'low_stock',
            default                 => 'in_stock',
        };
        $this->update(['stock_level' => $level]);
    }
}

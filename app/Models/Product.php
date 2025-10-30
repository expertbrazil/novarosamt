<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'profit_margin_percent',
        'sale_price',
        'unit',
        'unit_value',
        'stock',
        'image',
        'is_active',
        'last_purchase_cost',
        'last_purchase_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'profit_margin_percent' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'unit_value' => 'decimal:3',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'last_purchase_cost' => 'decimal:2',
        'last_purchase_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}


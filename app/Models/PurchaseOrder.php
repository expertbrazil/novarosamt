<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'notes',
        'created_by',
    ];

    public const STATUS_DRAFT = 'rascunho';
    public const STATUS_SENT = 'enviado';

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => 'Enviado',
            default => 'Rascunho',
        };
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_cpf',
        'customer_email',
        'customer_phone',
        'customer_address',
        'observations',
        'total',
        'status',
        'due_date',
        'payment_method',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'due_date' => 'date',
        'whatsapp_sent_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-50',
            'aguardando_pagamento' => 'bg-orange-100 text-orange-800 dark:bg-orange-600 dark:text-orange-50',
            'aprovado' => 'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-blue-50',
            'entregue' => 'bg-green-100 text-green-800 dark:bg-green-600 dark:text-green-50',
            'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-600 dark:text-red-50',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'Pendente',
            'aguardando_pagamento' => 'Aguardando Pagamento',
            'aprovado' => 'Aprovado',
            'entregue' => 'Entregue',
            'cancelado' => 'Cancelado',
            default => 'Desconhecido',
        };
    }
    
    public static function getStatusOptions(): array
    {
        return [
            'pendente' => 'Pendente',
            'aguardando_pagamento' => 'Aguardando Pagamento',
            'aprovado' => 'Aprovado',
            'entregue' => 'Entregue',
            'cancelado' => 'Cancelado',
        ];
    }
    
    public function getNextStatus(): ?string
    {
        return match($this->status) {
            'pendente' => 'aguardando_pagamento',
            'aguardando_pagamento' => 'aprovado',
            'aprovado' => 'entregue',
            'entregue' => null, // Não há próximo status
            'cancelado' => null, // Não há próximo status
            default => 'pendente',
        };
    }
}


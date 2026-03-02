<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // ============ СВЯЗИ ============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ============ МЕТОДЫ ============

    /**
     * Получить общую стоимость позиции
     */
    public function getTotal(): float
    {
        return $this->product->getCurrentPrice() * $this->quantity;
    }

    /**
     * Увеличить количество
     */
    public function increaseQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
        $this->save();
    }

    /**
     * Уменьшить количество
     */
    public function decreaseQuantity(int $amount = 1): bool
    {
        if ($this->quantity <= $amount) {
            return false;
        }
        
        $this->quantity -= $amount;
        $this->save();
        return true;
    }

    /**
     * Проверить, доступно ли количество
     */
    public function isQuantityAvailable(): bool
    {
        return $this->product->hasEnoughStock($this->quantity);
    }
}
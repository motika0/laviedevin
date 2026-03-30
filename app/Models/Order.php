<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'final_amount',
        'bonus_used',
        'status',
        'payment_status',
        'address',
        'phone',
        'comment',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function loyaltyHistory()
    {
        return $this->hasMany(LoyaltyHistory::class);
    }

    public function getTotalItems(): int
{
    return $this->items()->sum('quantity');
}

public function isCancellable(): bool
{
    return in_array($this->status, ['новый', 'в обработке']);
}

public function cancel(): bool
{
    if (!$this->isCancellable()) {
        return false;
    }
    
    $this->status = 'отменен';
    return $this->save();
}

public function markAsPaid(): void
{
    $this->payment_status = 'оплачен';
    $this->save();
}

public function updateStatus(string $status): bool
{
    $allowed = ['новый', 'в обработке', 'выполнен', 'отменен'];
    
    if (!in_array($status, $allowed)) {
        return false;
    }
    
    $this->status = $status;
    return $this->save();
}

public function getDiscountAmount(): float
{
    return $this->discount_amount ?? 0;
}

public static function generateOrderNumber(): string
{
    $date = now()->format('Ymd');
    $lastOrder = self::whereDate('created_at', today())
        ->orderBy('id', 'desc')
        ->first();
    
    $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;
    
    return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
}
}
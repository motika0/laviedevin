<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyHistory extends Model
{
    use HasFactory;

    protected $table = 'loyalty_history';

    protected $fillable = [
        'user_id',
        'points',
        'action',
        'order_id',
        'description',
    ];

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getSign(): string
{
    return $this->points > 0 ? '+' : '';
}

public function isAccrual(): bool
{
    return $this->points > 0;
}

public function isSpending(): bool
{
    return $this->points < 0;
}
}
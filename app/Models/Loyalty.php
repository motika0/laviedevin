<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    use HasFactory;

    protected $table = 'loyalty';

    protected $fillable = [
        'user_id',
        'points',
        'total_earned',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(LoyaltyHistory::class, 'user_id', 'user_id');
    }

    public function addPoints(int $points, string $description = null, ?Order $order = null): void
{
    $this->points += $points;
    $this->total_earned += $points;
    $this->updateLevel();
    $this->save();
    
    LoyaltyHistory::create([
        'user_id' => $this->user_id,
        'points' => $points,
        'action' => 'начисление',
        'order_id' => $order?->id,
        'description' => $description,
    ]);
}

public function spendPoints(int $points, string $description = null, ?Order $order = null): bool
{
    if ($this->points < $points) {
        return false;
    }
    
    $this->points -= $points;
    $this->save();
    
    LoyaltyHistory::create([
        'user_id' => $this->user_id,
        'points' => -$points,
        'action' => 'списание',
        'order_id' => $order?->id,
        'description' => $description,
    ]);
    
    return true;
}

protected function updateLevel(): void
{
    if ($this->total_earned >= 5000) {
        $this->level = 'золото';
    } elseif ($this->total_earned >= 2000) {
        $this->level = 'серебро';
    } else {
        $this->level = 'бронза';
    }
}

public function getConversionRate(): float
{
    return match($this->level) {
        'золото' => 1.0,
        'серебро' => 0.8,
        default => 0.5,
    };
}

public function getMaxDiscount(): int
{
    return (int)($this->points * $this->getConversionRate());
}
}
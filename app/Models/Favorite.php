<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
    public static function addToFavorites(int $userId, int $productId): self
{
    return self::firstOrCreate([
        'user_id' => $userId,
        'product_id' => $productId,
    ]);
}

public static function removeFromFavorites(int $userId, int $productId): bool
{
    return self::where('user_id', $userId)
        ->where('product_id', $productId)
        ->delete() > 0;
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'volume',
        'alcohol',
        'country',
        'stock',
        'image',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

public function getCurrentPrice(): float
{
    return $this->old_price ?? $this->price;
}

public function hasDiscount(): bool
{
    return !is_null($this->old_price) && $this->old_price > 0;
}

public function getDiscountPercent(): ?int
{
    if (!$this->hasDiscount()) {
        return null;
    }
    
    if ($this->old_price <= $this->price) {
        return null;
    }
    
    return round((($this->old_price - $this->price) / $this->old_price) * 100);
}

public function getAverageRating(): float
{
    return $this->reviews()
        ->where('is_approved', true)
        ->avg('rating') ?? 0;
}

public function getReviewsCount(): int
{
    return $this->reviews()
        ->where('is_approved', true)
        ->count();
}

public function inStock(): bool
{
    return $this->stock > 0;
}

public function hasEnoughStock(int $quantity): bool
{
    return $this->stock >= $quantity;
}

public function decreaseStock(int $quantity): bool
{
    if (!$this->hasEnoughStock($quantity)) {
        return false;
    }
    
    $this->stock -= $quantity;
    return $this->save();
}

public function increaseStock(int $quantity): void
{
    $this->stock += $quantity;
    $this->save();
}

public function getRelatedProducts(int $limit = 4)
{
    return self::where('category_id', $this->category_id)
        ->where('id', '!=', $this->id)
        ->where('is_active', true)
        ->limit($limit)
        ->get();
}

public function getFullName(): string
{
    return $this->name . ' (' . $this->volume . ' л)';
}

public function getUrl(): string
{
    return route('product.show', $this->id);
}

public function getImageUrl(): string
{
    if ($this->image) {
        return asset($this->image); 
    }
    return asset('images/no-image.jpg');
}
}

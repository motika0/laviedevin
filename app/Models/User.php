<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'password',
        'phone',
        'birth_date',
        'is_verified',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
    
    public function loyalty()
    {
        return $this->hasOne(Loyalty::class);
    }

    public function loyaltyHistory()
    {
        return $this->hasMany(LoyaltyHistory::class);
    }

    public function ageVerification()
    {
        return $this->hasOne(AgeVerification::class, 'user_id');
    }

    public function getFullName(): string
    {
        return trim($this->surname . ' ' . $this->name . ' ' . $this->patronymic);
    }

    public function getInitials(): string
    {
        $initials = $this->surname . ' ';
        $initials .= mb_substr($this->name, 0, 1) . '.';
        if ($this->patronymic) {
            $initials .= mb_substr($this->patronymic, 0, 1) . '.';
        }
        return $initials;
    }

    public function isAdult(): bool
    {
        return $this->birth_date && $this->birth_date->age >= 18;
    }

    public function getBonusPoints(): int
    {
        return $this->loyalty ? $this->loyalty->points : 0;
    }

    public function getLoyaltyLevel(): string
    {
        return $this->loyalty ? $this->loyalty->level : 'бронза';
    }

    public function getTotalSpent(): float
    {
        return $this->orders()
            ->where('status', 'выполнен')
            ->sum('final_amount');
    }

    public function getOrdersCount(): int
    {
        return $this->orders()->count();
    }

    public function getCartItems()
    {
        return $this->cart()->with('product')->get();
    }

    public function getCartTotal(): float
    {
        return $this->cart()
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->getCurrentPrice() * $item->quantity;
            });
    }

    public function getFavorites()
    {
        return $this->favorites()->with('product')->get();
    }

    public function hasInFavorites(int $productId): bool
    {
        return $this->favorites()
            ->where('product_id', $productId)
            ->exists();
    }

    public function hasInCart(int $productId): bool
    {
        return $this->cart()
            ->where('product_id', $productId)
            ->exists();
    }

    public function clearCart(): void
    {
        $this->cart()->delete();
    }
}
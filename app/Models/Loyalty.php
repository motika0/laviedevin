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

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(LoyaltyHistory::class, 'user_id', 'user_id');
    }
}
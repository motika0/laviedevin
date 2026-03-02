<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeVerification extends Model
{
    use HasFactory;

    protected $table = 'age_verification';

    protected $fillable = [
        'user_id',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
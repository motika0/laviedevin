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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
{
    return !is_null($this->verified_at);
}

public function verifyNow(): void
{
    $this->verified_at = now();
    $this->save();
}

public function getVerifiedAtFormatted(): string
{
    return $this->verified_at ? $this->verified_at->format('d.m.Y H:i') : 'Не подтвержден';
}
}
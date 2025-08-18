<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    /** @use HasFactory<\Database\Factories\OTPFactory> */
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'user_id',
        'otp_code',
        'purpose',
        'expires_at',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->verified_at === null && $this->expires_at > now();
    }

    public function isUsed(): bool
    {
        return $this->verified_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }
}

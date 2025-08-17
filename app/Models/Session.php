<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Session extends Model
{
    use HasUlids, SoftDeletes;

    protected $table = 'sessions';

    protected $fillable = [
        'user_id',
        'ip_address',
        'device_info',
        'user_agent',
        'token',
        'expires_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'device_info' => 'array',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function refresh()
    {
        $this->update(['last_accessed_at' => now()]);
    }

    public function extend($minutes = 60)
    {
        $this->update(['expires_at' => now()->addMinutes($minutes)]);
    }

    public function getDeviceNameAttribute()
    {
        return $this->device_info['device_name'] ?? 'Unknown Device';
    }

    public function getBrowserAttribute()
    {
        return $this->device_info['browser'] ?? 'Unknown Browser';
    }

    public function getLocationAttribute()
    {
        return $this->device_info['location'] ?? 'Unknown Location';
    }

    // Static methods for session management
    public static function createForUser($user, $ipAddress, $userAgent, $deviceInfo = [])
    {
        return static::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_info' => $deviceInfo,
            'token' => Str::random(64),
            'expires_at' => now()->addHours(24), // 24 hour session
            'last_accessed_at' => now(),
        ]);
    }

    public static function invalidateUserSessions($userId, $exceptSessionId = null)
    {
        $query = static::where('user_id', $userId);

        if ($exceptSessionId) {
            $query->where('id', '!=', $exceptSessionId);
        }

        return $query->update(['expires_at' => now()]);
    }

    public static function cleanupExpiredSessions()
    {
        return static::expired()->delete();
    }
}

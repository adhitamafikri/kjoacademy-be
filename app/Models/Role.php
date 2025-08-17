<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'permissions' => 'array',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Helper methods
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function isAdmin()
    {
        return $this->name === 'admin';
    }

    public function isStudent()
    {
        return $this->name === 'student';
    }

    // Static methods for role management
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}

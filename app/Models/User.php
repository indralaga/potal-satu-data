<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'opd_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function opd()
    {
        return $this->belongsTo(OPD::class);
    }

    public function datasets()
    {
        return $this->hasMany(Dataset::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin_portal', 'admin_opd']);
    }
}

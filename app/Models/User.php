<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'email', 'password', 'level', 'type',
    ];

    /**
     * Get the identifier that will be stored in the JWT token.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    // User can have one Vendor Account
    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'v_id');
    }
    // If one user can have multiple vendors
    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class, 'v_id');
    }
}

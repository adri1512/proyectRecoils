<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    
    protected $fillable = [
        'role',
        'name',
        'document_user',
        'email',
        'verification_token',
        'email_verified_at',
        'phone',
        'person_type',
        'password',
    ];

    // Relación con el modelo Address
    public function addresses()
    {
    return $this->hasMany(Address::class, 'id_client');
    }

    // Relación con el modelo PickupRequest
    public function pickuprequest()
    {
    return $this->hasMany(PickupRequest::class, 'id_client');
    }

    // Relación con el modelo PickupRequest
    public function pickuprequest_driver()
    {
    return $this->hasMany(PickupRequest::class, 'id_driver');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'verification_token',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

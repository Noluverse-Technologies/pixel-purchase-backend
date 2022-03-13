<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'wallet_address',
        'password',
        'role',
        'image',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'deleted_at'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * users table relationship with roles table
     */
    public function hasRole()
    {
        return $this->hasMany('Modules\Users\Entities\Roles', 'id', 'role');
    }

    /**
     * users table relationship with transaction table
     */
    public function hasTransaction()
    {
        return $this->hasMany('Modules\Payment\Entities\Transactions', 'user_id', 'id');
    }
}

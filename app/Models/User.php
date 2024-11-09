<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'full_name',
        'email',
        'username',
        'password',
        'balance',
        'phone_number',
        'level',
        'api_key',
        'whitelist_ip',
        'is_active'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function deposit(){
        return $this->hasMany(Deposit::class);
    }

    public function mutation(){
        return $this->hasMany(BalanceMutation::class);
    }

    public function user_upgrade(){
        return $this->hasMany(UserUpgrade::class);
    }

    public function user_token(){
        return $this->hasMany(UserToken::class);
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($user) {
            $user->order()->delete();
            $user->deposit()->delete();
            $user->mutation()->delete();
            $user->user_upgrade()->delete();
            $user->user_token()->delete();
        });
    }
}

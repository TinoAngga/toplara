<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'slug',
        'fee',
        'fee_percent',
        'img',
        'description',
        'information',
        'min_amount',
        'max_amount',
        'payment_gateway',
        'payment_gateway_code',
        'time_used',
        'time_stopped',
        'is_qrcode',
        'qrcode',
        'is_manual',
        'is_public',
        'is_active'
    ];

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function deposit(){
        return $this->hasMany(Deposit::class);
    }

    public function user_upgrade(){
        return $this->hasMany(UserUpgrade::class);
    }

    public function scopePaymentPublicActive($query){
        return (user())
            ? $query
                ->where('is_active', 1)
            :
            $query
                ->where('is_public', 1)
                ->where('is_active', 1)
            ;
    }

    public function scopePaymentActive($query){
        return $query->where('is_active', 1);
    }
}

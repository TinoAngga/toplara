<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUpgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'invoice',
        'level',
        'price',
        'unique_code',
        'fee',
        'is_paid',
        'status',
        'ip_address',
        'payment_gateway_request_response',
        'payment_gateway_callback_response'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}

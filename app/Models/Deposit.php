<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'invoice',
        'amount',
        'balance',
        'unique_code',
        'fee',
        'is_paid',
        'status',
        'additional_data',
        'ip_address',
        'payment_gateway_request_response',
        'payment_gateway_callback_response'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function payment(){
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scopeByInvoice($query, $invoice)
    {
        return $query->where('invoice', $invoice);
    }
}

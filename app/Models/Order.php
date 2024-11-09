<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'user_id',
        'service_id',
        'payment_id',
        'provider_id',
        'invoice',
        'data',
        'additional_data',
        'additional_info',
        'price',
        'profit',
        'unique_code',
        'fee',
        'is_paid',
        'is_refund',
        'status',
        'ip_address',
        'order_type',
        'provider_order_id',
        'provider_order_description',
        'payment_gateway_request_response',
        'payment_gateway_callback_response',
        'email_order',
        'whatsapp_order',
        'is_api'
    ];

    public function provider() {
    	return $this->belongsTo(Provider::class);
    }

    public function payment() {
    	return $this->belongsTo(PaymentMethod::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function provider_log(){
        return $this->hasMany(ProviderApiLog::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($order) {
             $order->provider_log()->delete();
        });
    }

    public function scopeProviderLastLog($query, $order_id){
        return $query->where('order_id', $order_id)
            ->latest()
            ->first();
    }
    public function scopeByInvoice($query, $invoice){
        return $query->where('invoice', $invoice);
    }
}

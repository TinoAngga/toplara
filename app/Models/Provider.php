<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'api_username',
        'api_key',
        'api_additional',
        'api_url_order',
        'api_url_status',
        'api_url_service',
        'api_url_profile',
        'api_balance',
        'api_balance_alert',
        'is_auto_update',
        'is_manual',
        'is_active'
    ];

    public function service() {
    	return $this->hasMany(Service::class);
    }
    public function order() {
    	return $this->hasMany(Order::class);
    }
    public function provider_log(){
        return $this->hasMany(Provider::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($provider) {
            $provider->service()->delete();
            $provider->order()->delete();
            $provider->provider_log()->delete();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}

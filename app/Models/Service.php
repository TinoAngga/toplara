<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_type',
        'service_category_id',
        'provider_id',
        'provider_service_code',
        'name',
        'brand',
        'price',
        'profit_type',
        'profit',
        'profit_config',
        'description',
        'is_rate_coin',
        'rate_coin',
        'price_rate_coin',
        'is_active'
    ];

    public function category() {
    	return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function provider() {
    	return $this->belongsTo(Provider::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function subCategory(){
        return $this->belongsTo(ServiceSubCategory::class, 'sub_category', 'name');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_id');
    }



    public static function boot() {
        parent::boot();

        static::deleting(function($service) {
             $service->order()->delete();
        });
    }

    public function scopeByServiceCategoryID($query, $serviceCategoryID){
        return $query
            ->where('service_category_id', $serviceCategoryID)
            ->where('is_active', 1);
            // ->orderBy('price->public', 'ASC');
            // ->orderByRaw("CAST(JSON_EXTRACT(price,'$.public') AS INTEGER) ASC");
    }

    protected $casts = [
        'price' => 'object',
        'profit' => 'object',
        'profit_config' => 'object'
    ];

}

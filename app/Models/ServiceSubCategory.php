<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'name',
        'slug'
    ];

    public function category() {
    	return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function service() {
    	return $this->hasMany(Service::class, 'sub_category', 'name');
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($serviceSubCategory) {
            // $serviceSubCategory->service()->delete();
        });
    }
}

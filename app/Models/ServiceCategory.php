<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'real_service_type',
        'service_type',
        'name',
        'sub_name',
        'slug',
        'brand',
        'get_nickname_code',
        'img',
        'guide_img',
        'description',
        'information',
        'is_additional_data',
        'is_check_id',
        'is_active',
        'form_setting',
        'counter'
    ];

    public function service() {
    	return $this->hasMany(Service::class, 'service_category_id');
    }

    public function serviceCategoryType()
    {
        return $this->belongsTo(ServiceCategoryType::class, 'service_type', 'slug');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_category_id');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', 1);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query
            ->where('slug', $slug);
    }

    protected $casts = [
        'form_setting' => 'array'
    ];
}

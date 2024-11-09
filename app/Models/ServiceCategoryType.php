<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategoryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'position',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', 1);
    }

    public function categories()
    {
        return $this->hasMany(ServiceCategory::class, 'service_type', 'slug');
    }
    
}

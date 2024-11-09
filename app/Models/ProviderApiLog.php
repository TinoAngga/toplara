<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider_id',
        'description',
        'order_response',
        'status_response',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}


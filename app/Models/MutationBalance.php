<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'description',
        'amount',
        'beginning_balance',
        'last_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;

class Admin extends Authenticable {
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'full_name', 'username', 'password', 'level'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function logs()
    {
        return $this->hasMany(AdminLog::class);
    }

}

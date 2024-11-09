<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (admin()) {
            if (admin()->is_active == 0) {
                Auth::guard('admin')->logout();
                session()->flash('alertClass', 'danger');
                session()->flash('alertTitle', 'Gagal.');
                session()->flash('alertMsg', 'Akun dinonatifkan.');
                return 'admin/login';
            }
            return 'admin';
        } elseif (user()) {
            if (user()->is_active == 0) {
                Auth::logout();
                session()->flash('alertClass', 'danger');
                session()->flash('alertTitle', 'Gagal.');
                session()->flash('alertMsg', 'Akun dinonatifkan.');
                return 'auth/login';
            }
            return 'account';
        } 

    }
}

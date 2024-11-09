<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $guard = null) {

        if ($request->segment(1) == 'admin') {
            if (admin()) {
                if (user()->is_active == 0) {
                    session()->flash('alertClass', 'danger');
                    session()->flash('alertTitle', 'Gagal.');
                    session()->flash('alertMsg', 'Akun dinonatifkan.');
                    Auth::guard('admin')->logout();
                    return redirect('admin/auth/login');
                }
                return redirect()->route('admin.dashboard');
            }
        } else {
            if (user()) {
                if (user()->is_active == 0) {
                    Auth::logout();
                    session()->flash('alertClass', 'danger');
                    session()->flash('alertTitle', 'Gagal.');
                    session()->flash('alertMsg', 'Akun dinonatifkan.');
                    return redirect('auth/login');
                }
                return redirect()->route('account.index');
            }
        }
        return $next($request);
    }
}

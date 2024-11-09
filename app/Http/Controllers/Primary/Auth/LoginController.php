<?php

namespace App\Http\Controllers\Primary\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Primary\Auth\LoginRequest;

class LoginController extends Controller
{
    public function view()
    {
        abort(404);
        $page = [
            'title' => 'Login',
            'breadcrumb' => [
                'first' => 'Login'
            ]
        ];
        return view('primary.auth.login', compact('page'));
    }

    public function login(LoginRequest $request)
    {
        abort(404);
        if(!$request->ajax()) abort(405);
        if (Auth::attempt($request->only('username', 'password'), $request->remember)) {
            if (Auth::user()->is_active == 0){
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Akun anda telah di nonaktifkan !! Harap hubungin admin !!.'
                ]);
            }
            session()->flash('alertClass', 'success');
            session()->flash('alertTitle', 'Berhasil.');
            session()->flash('alertMsg', 'Selamat datang <b>'.Auth::user()->full_name.'</b>.');
            return response()->json([
                'status' => true,
                'type' => 'alert',
                'msg' => 'Login berhasil !!.'
            ]);
        }
        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Username atau password tidak sesuai.'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('alertClass', 'success');
        session()->flash('alertTitle', 'Berhasil.');
        session()->flash('alertMsg', 'Logout berhasil !!.');
        return redirect(url('/home'));
    }
}

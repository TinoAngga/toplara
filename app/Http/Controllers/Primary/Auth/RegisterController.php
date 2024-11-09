<?php

namespace App\Http\Controllers\Primary\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Primary\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function view()
    {
        abort(404);
        $page = [
            'title' => 'Register',
            'breadcrumb' => [
                'first' => 'Register'
            ]
        ];
        return view('primary.auth.register', compact('page'));
    }

    public function register(RegisterRequest $request)
    {
        abort(404);
        if(!$request->ajax()) abort(405);
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'balance' => 0,
            'phone_number' => $request->phone_number,
            'level' => 'public',
            'is_active' => 1,
            'is_read_popup' => 0
        ]);
        Auth::login($user);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Register berhasil !!.'
        ]);
    }
}

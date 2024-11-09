<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\AdminLog;
use Stevebauman\Location\Facades\Location;

class AuthController extends Controller {
    public function __construct() {
        $this->middleware('guest:admin')->only('login', 'actionLogin');
    }
    public function login() {
        $page = [
            'title' => 'Login'
        ];
        return view('admin.auth.login', compact('page'));
    }
    public function actionLogin(LoginRequest $request) {
		if ($request->ajax() == false) abort('404');

        if (Auth::guard('admin')->attempt($request->only('username', 'password'), $request->remember) == true) {
            if (Auth::guard('admin')->user()->is_active == 0) {
                Auth::guard('admin')->logout();
                return response()->json([
                    'status'  => false,
                    'type'    => 'alert',
                    'msg' => 'Akun dinonaktifkan.'
                ]);
            }
            $location = Location::get($request->ip());
            $admin = Auth::guard('admin')->user();
            $adminLog = new AdminLog();
            $adminLog->admin_id = $admin->id;
            $adminLog->action = 'login';
            $adminLog->ip_address = $request->ip();
            $adminLog->user_agent = $request->header('User-Agent');
            $adminLog->payload = '';
            $adminLog->save();

            session()->flash('alertClass', 'success');
            session()->flash('alertTitle', 'Berhasil.');
            session()->flash('alertMsg', 'Selamat datang <b>'.$admin->full_name.'</b>.');
            return response()->json([
                'status'  => true,
                'type'    => 'alert',
                'msg' => 'Login Berhasil.'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'type'    => 'alert',
                'msg' => 'Username atau Password salah.'
            ]);
        }
    }
    public function logout(){
		if (Auth::guard('admin')->check() == false) return redirect('admin/auth/login');


        $adminLog = new AdminLog();
        $adminLog->admin_id = admin()->id;
        $adminLog->action = 'logout';
        $adminLog->ip_address = request()->ip();
        $adminLog->user_agent = request()->header('User-Agent');
        $adminLog->payload = '';
        $adminLog->save();

        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}

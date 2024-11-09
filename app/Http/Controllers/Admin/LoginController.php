<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Primary\Auth\LoginRequest;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class LoginController extends Controller
{
    public function index()
    {
        $page = 'Login Admin Dashboard';
        return view('admin.login', compact('page'));
    }

    public function action(LoginRequest $request) {
		if ($request->ajax() == false) abort('404');
        if (Auth::guard('admin')->attempt($request->only('username', 'password'), $request->remember) == true) {
            if (admin()->is_active == 0) {
                Auth::guard('admin')->logout();
                return response()->json([
                    'status'  => false,
                    'type'    => 'alert',
                    'msg' => 'Akun dinonaktifkan.'
                ]);
            }

            $location = $this->getLocation();


            $admin = Auth::guard('admin')->user();
            $adminLog = new AdminLog();
            $adminLog->admin_id = $admin->id;
            $adminLog->action = 'login';
            $adminLog->ip_address = $request->ip();
            $adminLog->user_agent = $request->header('User-Agent');
            $adminLog->payload = $location;
            $adminLog->save();

            session()->flash('alertClass', 'success');
            session()->flash('alertTitle', 'Berhasil.');
            session()->flash('alertMsg', 'Selamat datang <b>'.admin()->full_name.'</b>.');
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

        $location = $this->getLocation();

        $adminLog = new AdminLog();
        $adminLog->admin_id = admin()->id;
        $adminLog->action = 'logout';
        $adminLog->ip_address = request()->ip();
        $adminLog->user_agent = request()->header('User-Agent');
        $adminLog->payload = $location;
        $adminLog->save();

        Auth::guard('admin')->logout();
        return redirect('admin/auth/login');
    }

    protected function getLocation()
    {
        $location = Location::get(request()->ip());
        if ($location == null) {
            $arrLocation = [
                'ip' => request()->ip(),
                'countryCode' => '',
                'countryName' => '',
                'regionCode' => '',
                'regionName' => '',
                'cityName' => '',
                'zipCode' => '',
                'isoCode' => '',
                'postalCode' => '',
                'latitude' => '',
                'longitude' => '',
                'metroCode' => '',
                'areaCode' => '',
            ];
        } else{
            $arrLocation = [
                'ip' => $location->ip,
                'countryCode' => $location->countryCode,
                'countryName' => $location->countryName,
                'regionCode' => $location->regionCode,
                'regionName' => $location->regionName,
                'cityName' => $location->cityName,
                'zipCode' => $location->zipCode,
                'isoCode' => $location->isoCode,
                'postalCode' => $location->postalCode,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'metroCode' => $location->metroCode,
                'areaCode' => $location->areaCode,
            ];
        }
        return $arrLocation;
    }
}

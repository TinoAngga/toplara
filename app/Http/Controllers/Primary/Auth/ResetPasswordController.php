<?php

namespace App\Http\Controllers\Primary\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Primary\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Models\UserToken;

class ResetPasswordController extends Controller
{
    public function view(UserToken $userToken)
    {
        if ($userToken == false) {
            session()->flash('alertClass', 'danger');
            session()->flash('alertTitle', 'Gagal !!.');
            session()->flash('alertMsg', 'Permintaan reset password tidak ditemukan !!.');
            return redirect()->route('auth.forgot-password');
        }
        $page = [
            'title' => 'Reset Password',
            'breadcrumb' => [
                'first' => 'Reset Password'
            ]
        ];
        return view('primary.auth.reset-password', compact('page', 'userToken'));
    }

    public function resetPassword(ResetPasswordRequest $request, UserToken $userToken)
    {
        if(!$request->ajax()) abort(405);
        $user = User::find($userToken->user_id);
        if ($user == false) return response()->json(['status' => false, 'type' => 'alert', 'msg' => 'Pengguna tidak ditemukan.']);
        $user->password = bcrypt($request->password);
        $user->save();
        $userToken->delete();
        session()->flash('alertClass', 'success');
        session()->flash('alertTitle', 'Berhasil !!.');
        session()->flash('alertMsg', 'Reset password berhasil !. Silahkan Login !!.');
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Reset password berhasil.',
        ]);
    }

}

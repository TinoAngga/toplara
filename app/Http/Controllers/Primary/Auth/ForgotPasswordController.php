<?php

namespace App\Http\Controllers\Primary\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Primary\Auth\ForgotPasswordRequest;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function view()
    {
        $page = [
            'title' => 'Lupa Password',
            'breadcrumb' => [
                'first' => 'Lupa Password'
            ]
        ];
        return view('primary.auth.forgot-password', compact('page'));
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        if(!$request->ajax()) abort(405);
        $user = User::where('username', $request->username)->first();
        if ($user) {
            try {
                $data_token = [
                    'token' => md5($user->username . time()),
                    'type' => 'forgot',
                    'created_at' => date('Y-m-d H:i:s'),
                    'expired_at' => date('Y-m-d H:i:s', strtotime('+1 day', strtotime(date('Y-m-d H:i:s'))))
                ];
                $user->user_token()->create($data_token);
                $details = [
                    'username' => $user->username,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'url' => url('auth/reset-password/' . $data_token['token'])
                ];
                \Mail::send('mail.auth.forgot-password', $details, function($message) use ($details) {
                    $message
                     ->to($details['email'], $details['full_name'])
                     ->from(config('mail.from.address'), config('mail.from.name'))
                     ->subject('Atur Ulang Kata Sandi - ' . getConfig('title'));
                 });
                 return response()->json([
                    'status'  => true,
                    'type' => 'alert',
                    'msg' => 'Silahkan periksa Email anda untuk mengatur ulang kata sandi akun Anda.'
                ]);
            } catch (\Exception $message) {
                \Log::error($message->getMessage());
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Terjadi kesalahan pada sistem !! harap hubungi admin !!.'
                ]);
            }
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => 'Pengguna tidak di temukan.'
            ]);
        }

        return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Pengguna tidak di temukan.'
        ]);
    }

}

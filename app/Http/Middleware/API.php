<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class API {
    public function handle(Request $request, Closure $next) {
        if (!in_array($request->action, ['profile', 'check-nickname', 'service', 'order', 'status'])) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter action tidak sesuai.'
            ]);
        }
        if (!$request->has('api_key')) {
            return response()->json([
                'status'  => false,
                'message' => 'API Key tidak ditemukan.',
            ]);
        }
        $user = User::where('api_key', $request->api_key)
            ->first();
        if ($user == null) {
            return response()->json([
                'status'  => false,
                'message' => 'API Key tidak sesuai.',
            ]);
        }
        if ($user->is_active == 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Akun anda dinonaktifkan.',
            ]);
        }
        if ($user->level !== 'h2h') {
            return response()->json([
                'status'  => false,
                'message' => 'Anda tidak memiliki akses.',
            ]);
        }
        $whitelistIP = explode(',', $user->whitelist_ip);
        if (!in_array($request->ip(), $whitelistIP)) {
            return response()->json([
                'status'  => false,
                'message' => 'IP ' .$request->ip(). ' anda tidak diizinkan.',
            ]);
        }
        $request->attributes->add(['user' => $user]);
        return $next($request);
    }
}

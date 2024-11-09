<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        $page = 'Pengguna';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $level = config('constants.options.member_level');
        return $dataTable->render('admin.user.index', compact('page', 'status', 'level'));
    }

    public function create()
    {
        $level = config('constants.options.member_level');
        return view('admin.user.create', compact('level'));
    }

    public function store(UserRequest $request)
    {
        if(!$request->ajax()) abort(405);
        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->balance = $request->balance;
        $user->phone_number = $request->phone_number;
        $user->level = $request->level;
        $user->is_active = $request->is_active ?? 0;
        $user->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Pengguna berhasil ditambahkan #' . $user->id . '.'
        ]);
    }

    public function show(User $user)
    {
        $user->with('order', 'deposit');
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $level = config('constants.options.member_level');
        return view('admin.user.edit', compact('user', 'level'));
    }

    public function update(Request $request, User $user)
    {
        $isExists = User::where('username', $request->username)->first();
        if ($request->username <> $user->username AND $isExists) {
            $validator = makeValidator($request->all(), [
                'username' => 'required|unique:users,username|min:6|max:50',
            ], [], ['username' => 'Username']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $isExists = User::where('email', $request->email)->first();
        if ($request->email <> $user->email AND $isExists) {
            $validator = makeValidator($request->all(), [
                'email' => 'required|unique:users,email',
            ], [], ['email' => 'Email']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $isExists = User::where('phone_number', $request->phone_number)->first();
        if ($request->phone_number <> $user->phone_number AND $isExists) {
            $validator = makeValidator($request->all(), [
                'phone_number' => 'required|numeric|min:10|phone_number|unique:users,phone_number',
            ], ['phone_number.phone_number' => 'Harus diawali dengan 62'], ['phone_number' => 'Nomor Handphone atau Whatsapp']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        if(!$request->ajax()) abort(405);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = !is_null($request->password) ? bcrypt($request->password) : $user->password;
        $user->balance = $request->balance;
        $user->phone_number = $request->phone_number;
        $user->level = $request->level;
        $user->is_active = $request->is_active ?? 0;
        $user->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Pengguna berhasil diubah #' . $user->id . '.'
        ]);
    }

    public function destroy(User $user)
    {
        $user->order()->delete();
        $user->deposit()->delete();
        $user->mutation()->delete();
        $user->user_upgrade()->delete();
        $user->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Pengguna berhasil dihapus #' . $user->id . '.'
        ]);
    }

    public function switchStatus(Request $request, User $user){
        if ($request->ajax() == false) abort('404');
        if (!in_array($request->type, ['status']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $user->is_active = $request->value;
        $user->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$user->id.'.'
        ]);
    }
}

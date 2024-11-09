<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AdminDataTable;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;

class AdminController extends Controller
{
    public function index(AdminDataTable $dataTable)
    {
        $page = 'Admin';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $level = config('constants.options.admin_level');
        return $dataTable->render('admin.admin.index', compact('page', 'status', 'level'));
    }

    public function create()
    {
        $level = config('constants.options.admin_level');
        return view('admin.admin.create', compact('level'));
    }

    public function store(AdminRequest $request)
    {
        if(!$request->ajax()) abort(405);
        $admin = new Admin();
        $admin->full_name = $request->full_name;
        $admin->username = $request->username;
        $admin->password = bcrypt($request->password);
        $admin->level = $request->level;
        $admin->is_active = $request->is_active ?? 0;
        $admin->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Admin berhasil ditambahkan #' . $admin->id . '.'
        ]);
    }

    public function show(Admin $admin)
    {
        return view('admin.admin.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        $level = config('constants.options.admin_level');
        return view('admin.admin.edit', compact('admin', 'level'));
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        if(!$request->ajax()) abort(405);
        $isExists = Admin::where('username', $request->username)->first();
        if ($request->username <> $admin->username AND $isExists) {
            $validator = makeValidator($request->all(), [
                'username' => 'required|unique:admins,username|min:6|max:50',
            ], [], ['username' => 'Username']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        if(!$request->ajax()) abort(405);
        $admin->full_name = $request->full_name;
        $admin->username = $request->username;
        $admin->password = !is_null($request->password) ? bcrypt($request->password) : $admin->password;
        $admin->level = $request->level;
        $admin->is_active = $request->is_active ?? 0;
        $admin->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Admin berhasil diubah #' . $admin->id . '.'
        ]);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Admin berhasil dihapus #' . $admin->id . '.'
        ]);
    }

    public function switchStatus(Request $request, Admin $admin){
        if ($request->ajax() == false) abort('404');
        if (!in_array($request->type, ['status']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $admin->is_active = $request->value;
        $admin->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$admin->id.'.'
        ]);
    }
}

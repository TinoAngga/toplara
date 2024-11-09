<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AdminLogDataTable;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminLog;

class AdminLogController extends Controller
{
    public function index(AdminLogDataTable $dataTable)
    {
        $page = 'Admin Log';
        $admins = Admin::select('id', 'username')->get();
        return $dataTable->render('admin.admin-log.index', compact('page', 'admins'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(AdminLog $adminLog)
    {
        return view('admin.admin-log.show', compact('adminLog'));
    }

    public function edit(AdminLog $admin)
    {
        abort(404);
    }

    public function update(Request $request, Admin $admin)
    {
        abort(404);
    }

    public function destroy(AdminLog $admin)
    {
        abort(404);
    }

}

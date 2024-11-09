<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserLevelDataTable;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLevelController extends Controller
{
    public function index(UserLevelDataTable $dataTable)
    {
        $page = 'User Level';
        return $dataTable->render('admin.user-level.index', compact('page'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(UserLevel $userLevel)
    {

    }


    public function edit(UserLevel $userLevel)
    {
        return view('admin.user-level.edit', compact('userLevel'));
    }


    public function update(Request $request, UserLevel $userLevel)
    {
        $validator = \Validator::make($request->all(), [
            'price' => 'required|numeric',
            'get_balance' => 'required|numeric',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'msg' => $validator->errors()->toArray()
            ]);
        }
        $userLevel->update($request->all());
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Level berhasil diubah #' . $userLevel->id . '.'
        ]);
    }


    public function destroy(UserLevel $userLevel)
    {
        //
    }
}

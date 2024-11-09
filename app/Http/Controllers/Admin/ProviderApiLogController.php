<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProviderApiLog;
use App\DataTables\Admin\ProviderApiLogDataTable;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderApiLogController extends Controller
{
    public function index(ProviderApiLogDataTable $dataTable)
    {
        $page = 'Provider Log';
        $providers = Provider::all();
        return $dataTable->render('admin.provider-api-log.index', compact('page', 'providers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ProviderApiLog $providerApiLog)
    {
        return view('admin.provider-api-log.show', compact('providerApiLog'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(ProviderApiLog $providerApiLog)
    {
        $providerApiLog->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$providerApiLog->id.'.'
        ]);
    }
}

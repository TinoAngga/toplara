<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ServiceCategoryTypeDataTable;
use App\Http\Requests\Admin\ServiceCategoryTypeRequest;
use App\Models\ServiceCategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceCategoryTypeController extends Controller
{
    public function index(ServiceCategoryTypeDataTable $dataTable)
    {
        $page = 'Tipe Kategori Layanan';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        return $dataTable->render('admin.service-category-type.index', compact('page', 'status'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort(405);
        abort(404);
    }

    public function store(ServiceCategoryTypeRequest $request)
    {
        if ($request->ajax() == false) abort(405);
        abort(404);
    }

    public function show(ServiceCategoryType $serviceCategoryType)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.service-category-type.show', compact('serviceCategoryType'));
    }

    public function edit(ServiceCategoryType $serviceCategoryType)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.service-category-type.edit', compact('serviceCategoryType'));
    }

    public function update(ServiceCategoryTypeRequest $request, ServiceCategoryType $serviceCategoryType)
    {
        if ($request->ajax() == false) abort(405);
        $serviceCategoryType->icon = strtolower($request->icon);
        $serviceCategoryType->position = $request->position;
        $serviceCategoryType->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil diubah #' . $serviceCategoryType->id . '.'
        ]);
    }

    public function destroy(ServiceCategoryType $serviceCategoryType)
    {
        return abort(404);
        // $serviceCategoryType->delete();
        // return response()->json([
        //     'status'  => true,
        //     'type' => 'alert',
        //     'msg' => 'Berhasil menghapus data #'.$serviceCategoryType->id.'.'
        // ]);
    }

    public function switchStatus(Request $request, ServiceCategoryType $serviceCategoryType){
        if ($request->ajax() == false) abort(405);
        if (!in_array($request->type, ['status']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $serviceCategoryType->is_active = $request->value;
        $serviceCategoryType->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$serviceCategoryType->id.'.'
        ]);
    }
}

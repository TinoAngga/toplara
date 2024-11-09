<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Admin\ServiceDataTable;
use App\Http\Requests\Admin\ServiceRequest;
use App\Libraries\CustomException;
use App\Models\ServiceSubCategory;

class ServiceController extends Controller
{

    public function index(ServiceDataTable $dataTable)
    {
        $page = 'Layanan';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $category = ServiceCategory::all();
        $provider = Provider::all();
        return $dataTable->render('admin.service.index', compact('page', 'status', 'category', 'provider'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort('404');
        $category = ServiceCategory::all();
        $provider = Provider::all();
        $subCategory = ServiceSubCategory::all();
        return view('admin.service.create', compact('category', 'provider', 'subCategory'));
    }

    public function store(ServiceRequest $request)
    {
        if ($request->ajax() == false) abort('404');
        try {
            $store = (new \App\Services\Admin\Service\StoreService())->handle($request);
            return response()->json([
                'status'  => true,
                'type' => 'alert',
                'msg' => 'Layanan berhasil ditambahkan #' . $store->id . '.'
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }

    public function storeMass(Request $request, Provider $provider){
        if ($request->ajax() == false) abort('404');
        $validator = makeValidator($request->all(), [
            'service_category_id' => 'required|exists:service_categories,id',
            'cut_string' => 'string|nullable',
            'profit_type_mass' => 'required|in:percent,flat',
            // 'add_string' => 'string',
            'place_string' => 'in:right,left',
            'profit_mass.public' => 'required',
            'profit_mass.silver' => 'required',
            'profit_mass.gold' => 'required',
            'profit_mass.vip' => 'required',
        ], [], [
            'service_category_id' => 'Kategori layanan',
            'cut_string' => 'String yang di potong',
            'profit_type_mass' => 'Tipe profit',
            // 'add_string' => 'String di tambahkan',
            // 'place_string' => 'Tempat string',
            'profit_mass.public' => 'Profit Publik',
            'profit_mass.silver' => 'Profit Silver',
            'profit_mass.gold' => 'Profit Gold',
            'profit_mass.vip' => 'Profit VIP',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'msg' => $validator->errors()->toArray()
            ]);
        }
        try {
            $store = (new \App\Services\Admin\Service\StoreMassService())->handle($request, $provider);
            return response()->json([
                'status'  => true,
                'type' => 'alert',
                'msg' => 'Layanan dari provider berhasil ditambahkan / diupdate sejumlah <b>'.$store['total'].'</b> layanan.'
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }

    public function show(Service $service)
    {
        if (request()->ajax() == false) abort('404');
        $service->load('category', 'provider');
        return view('admin.service.show', compact('service'));
    }

    public function edit(Service $service)
    {
        if (request()->ajax() == false) abort('404');
        $category = ServiceCategory::all();
        $provider = Provider::all();
        $subCategory = ServiceSubCategory::all();
        return view('admin.service.edit', compact('service', 'category', 'provider', 'subCategory'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        if ($request->ajax() == false) abort('404');
        try {
            (new \App\Services\Admin\Service\UpdateService())->handle($request, $service);
            return response()->json([
                'status'  => true,
                'type' => 'alert',
                'msg' => 'Layanan berhasil diubah #' . $service->id . '.'
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }

    public function destroy(Service $service)
    {
        $service->order()->delete();
        $service->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$service->id.'.'
        ]);
    }
    public function switchStatus(Request $request, Service $service){
        if ($request->ajax() == false) abort('404');
        if (!in_array($request->type, ['status']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $service->is_active = $request->value;
        $service->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$service->id.'.'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryType;
use Illuminate\Http\Request;
use App\DataTables\Admin\ServiceCategoryDataTable;
use App\Http\Requests\Admin\ServiceCategoryRequest;
use Buglinjo\LaravelWebp\Webp;

class ServiceCategoryController extends Controller
{
    public function index(ServiceCategoryDataTable $dataTable)
    {
        $page = 'Kategori Layanan';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $additional_data = ['1' => 'Ya', '0' => 'Tidak'];
        $check_id = ['1' => 'Ya', '0' => 'Tidak'];
        return $dataTable->render('admin.service-category.index', compact('page', 'status', 'additional_data', 'check_id'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort(405);
        $serviceType = ServiceCategoryType::all();
        return view('admin.service-category.create', compact('serviceType'));
    }

    public function store(ServiceCategoryRequest $request)
    {
        if ($request->ajax() == false) abort(405);
        if ((int) $request->is_check_id == 1) {
            $validator = makeValidator($request->all(), [
                'get_nickname_code' => 'required',
            ], [], ['get_nickname_code' => 'Kode Validasi Nickname']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $serviceCategory = new ServiceCategory();
        $serviceCategory->real_service_type = $request->service_type ?? 'top-up-game';
        $serviceCategory->service_type = $request->service_type;
        $serviceCategory->name = ucwords($request->name);
        $serviceCategory->slug = makeSlug($request->name);
        $serviceCategory->get_nickname_code = $request->get_nickname_code <> '' ? $request->get_nickname_code : null;
        $serviceCategory->description = $request->description;
        $serviceCategory->information = $request->information;
        if ($request->img) {
            $file = $request->file('img');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('img'));
                $file_name = makeSlug($request->name).'.webp';
                $file->save(config('constants.options.asset_img_service_category') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                $file->move(config('constants.options.asset_img_service_category'), $file_name);
            }
            $serviceCategory->img = $file_name;
		}
        if ($request->guide_img) {
            $file = $request->file('guide_img');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('guide_img'));
                $file_name = makeSlug($request->name).'.webp';
                $file->save(config('constants.options.asset_img_service_category_guide') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                $file->move(config('constants.options.asset_img_service_category_guide'), $file_name);
            }
            $serviceCategory->guide_img = $file_name;
		}
        $serviceCategory->is_additional_data = $request->is_additional_data ?? 0;
        $serviceCategory->is_check_id = $request->is_check_id ?? 0;
        $serviceCategory->is_active = $request->is_active ?? 0;
        $serviceCategory->form_setting = [
            'placeholder_data' => $request->form_setting['placeholder_data'] ?? null,
            'placeholder_additional_data' => $request->form_setting['placeholder_additional_data'] ?? null,
            'form_additional_data' => $request->form_setting['form_additional_data'] ?? null
        ];
        $serviceCategory->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil ditambahkan #' . $serviceCategory->id . '.'
        ]);
    }

    public function show(ServiceCategory $serviceCategory)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.service-category.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        if (request()->ajax() == false) abort(405);
        $serviceType = ServiceCategoryType::all();
        return view('admin.service-category.edit', compact('serviceCategory', 'serviceType'));
    }

    public function update(ServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        if ($request->ajax() == false) abort(405);
        if ((int) $request->is_check_id == 1) {
            $validator = makeValidator($request->all(), [
                'get_nickname_code' => 'required',
            ], [], ['get_nickname_code' => 'Kode Validasi Nickname']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        // $isExists = ServiceCategory::where('name', $request->name)->first();
        // if ($request->name <> $serviceCategory->name AND $isExists) {
        //     $validator = makeValidator($request->all(), [
        //         'name' => 'required|unique:service_categories,name|max:12',
        //     ], [], ['name' => 'Nama']);
        //     if ($validator->fails()) {
        //         return response()->json([
        //             'status'  => false,
        //             'type'    => 'validation',
        //             'msg' => $validator->errors()->toArray()
        //         ]);
        //     }
        // }
        $serviceCategory->service_type = $request->service_type;
        $serviceCategory->name = ucwords($request->name);
        $serviceCategory->slug = makeSlug($request->name);
        $serviceCategory->get_nickname_code = $request->get_nickname_code <> '' ? $request->get_nickname_code : null;
        $serviceCategory->description = $request->description;
        $serviceCategory->information = $request->information;
        if ($request->has('img')) {
            $validator = makeValidator($request->all(), [
                'img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ], [], ['img' => 'Gambar']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
            $file = $request->file('img');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('img'));
                $file_name = makeSlug($request->name).'.webp';
                if (file_exists(config('constants.options.asset_img_service_category') . $serviceCategory->img) AND $serviceCategory->image !== 'dummy-img.webp') {
                    if ($serviceCategory->img !== 'dummy-img.webp') {
                        unlink(config('constants.options.asset_img_service_category') . $serviceCategory->img);
                    }
                }
                $file->save(config('constants.options.asset_img_service_category') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                if (file_exists(config('constants.options.asset_img_service_category') . $serviceCategory->img) AND $serviceCategory->image !== 'dummy-img.webp') {
                    if ($serviceCategory->img !== 'dummy-img.webp') {
                        unlink(config('constants.options.asset_img_service_category') . $serviceCategory->img);
                    }
                }
                $file->move(config('constants.options.asset_img_service_category'), $file_name);
            }
            $serviceCategory->img = $file_name;
		}
        if ($request->has('guide_img')) {
            $validator = makeValidator($request->all(), [
                'guide_img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5000',
            ], [], ['guide_img' => 'Gambar Petunjuk']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
            $file = $request->file('guide_img');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('guide_img'));
                $file_name = makeSlug($request->name).'.webp';
                    if (file_exists(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img) AND $serviceCategory->guide_img !== 'dummy-img.webp') {
                        if ($serviceCategory->guide_img !== 'dummy-img.webp') {
                            unlink(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img);
                        }

                    }
                $file->save(config('constants.options.asset_img_service_category_guide') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                if (file_exists(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img) AND $serviceCategory->guide_img !== 'dummy-img.webp') {
                    if ($serviceCategory->guide_img !== 'dummy-img.webp') {
                        unlink(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img);
                    }

                }
                $file->move(config('constants.options.asset_img_service_category_guide'), $file_name);
            }
            $serviceCategory->guide_img = $file_name;
		}
        $serviceCategory->is_additional_data = $request->is_additional_data ?? 0;
        $serviceCategory->is_check_id = $request->is_check_id ?? 0;
        $serviceCategory->is_active = $request->is_active ?? 0;
        $serviceCategory->form_setting = [
            'placeholder_data' => $request->form_setting['placeholder_data'] ?? null,
            'placeholder_additional_data' => $request->form_setting['placeholder_additional_data'] ?? null,
            'form_additional_data' => $request->form_setting['form_additional_data'] ?? null
        ];
        $serviceCategory->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil diubah #' . $serviceCategory->id . '.'
        ]);
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->service()->delete();
        $serviceCategory->delete();
        if (file_exists(config('constants.options.asset_img_service_category') . $serviceCategory->img)) {
            if ($serviceCategory->img !== 'dummy-img.webp') {
                unlink(config('constants.options.asset_img_service_category') . $serviceCategory->img);
            }
        }
        if (file_exists(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img)) {
            if ($serviceCategory->guide_img !== 'dummy-img.webp') {
                unlink(config('constants.options.asset_img_service_category_guide') . $serviceCategory->guide_img);
            }
        }
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$serviceCategory->id.'.'
        ]);
    }

    public function switchStatus(Request $request, ServiceCategory $serviceCategory){
        if ($request->ajax() == false) abort(405);
        if (!in_array($request->type, ['status', 'zone_id']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $serviceCategory->is_active = $request->value;
        if ($request->type == 'zone_id') $serviceCategory->is_additional_data = $request->value;
        $serviceCategory->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$serviceCategory->id.'.'
        ]);
    }
}

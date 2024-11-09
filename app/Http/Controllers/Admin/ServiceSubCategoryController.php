<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\DataTables\Admin\ServiceCategoryDataTable;
use App\DataTables\Admin\ServiceSubCategoryDataTable;
use App\Http\Requests\Admin\ServiceSubCategoryRequest;
use App\Models\ServiceSubCategory;

class ServiceSubCategoryController extends Controller
{
    public function index(ServiceSubCategoryDataTable $dataTable)
    {
        if (request()->ajax() AND request()->has('__m') AND request()->__m == 'list') return $this->sortList();
        $page = 'Sub Kategori Layanan';
        $category = ServiceCategory::all();
        return $dataTable->render('admin.service-sub-category.index', compact('page', 'category'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort(405);
        $category = ServiceCategory::all();
        return view('admin.service-sub-category.create', compact('category'));
    }

    public function store(ServiceSubCategoryRequest $request)
    {
        if ($request->ajax() == false) abort(405);
        // $category = ServiceCategory::find($request->service_category_id); // SERVICE CATEGORY
        $serviceSubCategory = new ServiceSubCategory();
        // $serviceSubCategory->service_category_id = $category->id;
        $serviceSubCategory->name = strtoupper($request->name);
        $serviceSubCategory->slug = makeSlug($request->name);
        $serviceSubCategory->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Sub Kategori layanan berhasil ditambahkan #' . $serviceSubCategory->id . '.'
        ]);
    }

    public function show(ServiceSubCategory $serviceSubCategory)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.service-sub-category.show', compact('serviceCategory'));
    }

    public function edit(ServiceSubCategory $serviceSubCategory)
    {
        if (request()->ajax() == false) abort(405);
        $category = ServiceCategory::all();
        return view('admin.service-sub-category.edit', compact('serviceSubCategory'));
    }

    public function update(ServiceSubCategoryRequest $request, ServiceSubCategory $serviceSubCategory)
    {
        if ($request->ajax() == false) abort(405);
        // $category = ServiceCategory::find($request->service_category_id); // SERVICE CATEGORY
        // $serviceSubCategory->service_category_id = $category->id;
        $isExists = ServiceSubCategory::where('name', $request->name)->first();
        if ($request->name <> $serviceSubCategory->name AND $isExists) {
            $validator = makeValidator($request->all(), [
                'name' => 'required||unique:service_sub_categories,name',
            ], [], ['name' => 'name']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $serviceSubCategory->name = strtoupper($request->name);
        $serviceSubCategory->slug = makeSlug($request->name);

        $serviceSubCategory->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil diubah #' . $serviceSubCategory->id . '.'
        ]);
    }

    public function destroy(ServiceSubCategory $serviceSubCategory)
    {
        $serviceSubCategory->service()->delete();
        $serviceSubCategory->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$serviceSubCategory->id.'.'
        ]);
    }

    public function sortGET(Request $request)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.service-sub-category.sort');
    }

    public function sortPOST(Request $request)
    {
        foreach ($request->ids as $key => $value) {
            if ($value == null) continue;
            $subCategory = ServiceSubCategory::find($value);
            $subCategory->position = $key + 1;
            $subCategory->save();
        }
        return response()->json([
            'status' => true,
            'data' => ServiceSubCategory::query()->orderBy('position', 'ASC')->get(['id', 'slug', 'name', 'position'])
        ], 200);
    }

    protected function sortList()
    {
        $subCategories = ServiceSubCategory::query()->orderBy('position', 'ASC')->get(['id', 'name']);
        return response()->json([
            'status' => true,
            'data' => $subCategories
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BannerDataTable;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Buglinjo\LaravelWebp\Webp;

class BannerController extends Controller
{
    public function index(BannerDataTable $dataTable)
    {
        $page = 'Banner';
        return $dataTable->render('admin.banner.index', compact('page'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort('405');
        return view('admin.banner.create');
    }

    public function store(BannerRequest $request)
    {
        if ($request->ajax() == false) abort(405);
        $banner = new Banner();
        $banner->name = $request->name;
        $banner->url = $request->url;
        if ($request->value) {
			$file = $request->file('value');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('value'));
                $file_name = makeSlug($request->name).'.webp';
                $file->save(config('constants.options.asset_img_banner') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                $file->move(config('constants.options.asset_img_banner'), $file_name);
            }
            $banner->value = $file_name;
		}
        $banner->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Banner berhasil ditambahkan #' . $banner->id . '.'
        ]);
    }

    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        if ($request->ajax() == false) abort(405);
        $banner->name = $request->name;
        $banner->url = $request->url;
        if ($request->has('value')) {
            $validator = makeValidator($request->all(), [
                'value' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            ], [], ['value' => 'Gambar']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
			$file = $request->file('value');
            if ($file->getClientOriginalExtension() != 'webp') {
                $file = Webp::make($request->file('value'));
                $file_name = makeSlug($request->name) . '.webp';
                if (file_exists(config('constants.options.asset_img_banner') . $banner->value)) {
                    unlink(config('constants.options.asset_img_banner') . $banner->value);
                }
                $file->save(config('constants.options.asset_img_banner') . $file_name);
            } else {
                $file_name = makeSlug($request->name).'.'.$file->getClientOriginalExtension();
                if (file_exists(config('constants.options.asset_img_banner') . $banner->value)) {
                    unlink(config('constants.options.asset_img_banner') . $banner->value);
                }
                $file->move(config('constants.options.asset_img_banner'), $file_name);
            }

            $banner->value = $file_name;
		}
        $banner->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Banner berhasil diubah #' . $banner->id . '.'
        ]);
    }

    public function destroy(Banner $banner)
    {
        if (file_exists(config('constants.options.asset_img_banner') . $banner->value)) {
            unlink(config('constants.options.asset_img_banner') . $banner->value);
        }
        $banner->delete();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$banner->id.'.'
        ]);
    }
}

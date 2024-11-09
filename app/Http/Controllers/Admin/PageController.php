<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PageDataTable;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;

class PageController extends Controller
{
    public function index(PageDataTable $dataTable)
    {
        $page = 'Halaman';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        return $dataTable->render('admin.page.index', compact('page', 'status'));
    }

    public function create()
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.page.create');
    }

    public function store(PageRequest $request)
    {
        if ($request->ajax() == false) abort(405);
        $page = new Page();
        $page->title = ucwords($request->title);
        $page->slug = makeSlug($request->name);
        $page->content = $request->content;
        if ($request->img) {
			$file = $request->file('img');
            $file_name = makeSlug($request->title).'.'.$file->getClientOriginalExtension();
            $file->move(config('constants.options.asset_img_page'), $file_name);
            $page->img = $file_name;
		}
        $page->is_primary = 0;
        $page->is_active = $request->is_active ?? 0;
        $page->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil ditambahkan #' . $page->id . '.'
        ]);
    }

    public function show(Page $page)
    {
        if (request()->ajax() == false) abort(405);
        return view('admin.page.edit', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.page.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        if ($request->ajax() == false) abort(405);
        $isExists = Page::where('title', $request->title)->first();
        if ($request->title <> $page->title AND $isExists) {
            $validator = makeValidator($request->all(), [
                'title' => 'required|unique:pages,title|max:12',
            ], [], ['title' => 'Judul']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
        }
        $page->title = ucwords($request->title);
        $page->slug = makeSlug($request->title);
        $page->content = $request->content;
        if ($request->has('img')) {
            $validator = makeValidator($request->all(), [
                'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [], ['img' => 'Gambar']);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'type'    => 'validation',
                    'msg' => $validator->errors()->toArray()
                ]);
            }
			$file = $request->file('img');
            $file_name = makeSlug($request->title) . '.' . $file->getClientOriginalExtension();
            if (!is_null($page->img)) {
                if (file_exists(config('constants.options.asset_img_page') . $page->img)) {
                    unlink(config('constants.options.asset_img_page') . $page->img);
                }
            }
            $file->move(config('constants.options.asset_img_page'), $file_name);
            $page->img = $file_name;
		}
        $page->is_primary = $page->primary ?? 0;
        $page->is_active = $request->is_active ?? 0;
        $page->save();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Kategori layanan berhasil diubah #' . $page->id . '.'
        ]);
    }


    public function destroy(Page $page)
    {
        if ($page->is_primary == 1) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => $page->title . ' Hanya di perbolehkan update data !!'
            ]);
        }
        if (!is_null($page->img)) {
            if (file_exists(config('constants.options.asset_img_page') . $page->img)) {
                unlink(config('constants.options.asset_img_page') . $page->img);
            }
        }
        $page->delete();

        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$page->id.'.'
        ]);
    }

    public function switchStatus(Request $request, Page $page){
        if ($request->ajax() == false) abort(405);
        if (!in_array($request->type, ['status']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $page->is_active = $request->value;
        $page->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$page->id.'.'
        ]);
    }
}

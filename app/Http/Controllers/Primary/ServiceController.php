<?php

namespace App\Http\Controllers\Primary;

use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{

    public function index()
    {
        $page = [
            'title' => 'Daftar Layanan',
            'breadcrumb' => [
                'first' => 'Daftar Layanan'
            ]
        ];
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $category = ServiceCategory::active()->get();
        return view('primary.service.index', compact('page', 'status', 'category'));
    }

}

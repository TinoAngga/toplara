<?php

namespace App\DataTables\Admin;

use App\Models\Service;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('is_active', request('filter_status'));
                }
                if (request()->has('filter_category') AND request('filter_category') <> null) {
                    $query->where('service_category_id', request('filter_category'));
                }
                if (request()->has('filter_provider') AND request('filter_provider') <> null) {
                    $query->where('provider_id', request('filter_provider'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->whereHas('category', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhereHas('provider', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhere('id', 'like', "%".request('search')."%")
                        ->orWhere('name', 'like', "%".request('search')."%")
                        ->orWhere('provider_service_code', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('name', function ($query) {
                return strtoupper($query->name);
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->addColumn('status', 'admin.' .request()->segment(2). '.button.active')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'status', 'price', 'profit']);
    }

    public function query(Service $model)
    {
        return $model->query()->with('category:id,name', 'provider:id,name');
    }

    public function html() {
        return $this->builder()
                    ->setTableId('datatable')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        "responsive" => true,
                        "autoWidth" => false,
                        "pageLength" => 30,
                        "lengthMenu" => [5, 10, 30, 50, 100],
                        "pagingType" => "full_numbers",
                        "language" => [
                            "processing" => 'Sedang memproses...',
                            "lengthMenu" => "_MENU_",
                            "zeroRecords" => "Tidak ditemukan data yang sesuai",
                            "info" => "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            "infoEmpty" => "Menampilkan 0 sampai 0 dari 0 entri",
                            "infoFiltered" => "(disaring dari _MAX_ entri keseluruhan)",
                            "infoPostFix" => "",
                            "search" => "Cari:",
                            "paginate" => [
                                "first" => "Pertama",
                                "previous" => "<i class='mdi mdi-chevron-left'>",
                                "next" => "<i class='mdi mdi-chevron-right'>",
                                "last" =>    "Terakhir"
                            ],
                        ]
                    ])
                    ->dom('<bottom><"float-left"><"float-right">r<"row"<"col-sm-4"i><"col-sm-4"><"col-sm-4"p>>')
                    ->ajax([
                        'url' => url()->current(),
                        'data' => 'function(d) {
                            d.filter_status = $("#filter_status option:selected").val();
                            d.filter_category = $("#filter_category option:selected").val();
                            d.filter_provider = $("#filter_provider option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns() {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '20'],
            ['data' => 'provider.name', 'title' => 'PROVIDER', 'width' => '40'],
            ['data' => 'category.name', 'title' => 'KATEGORI', 'width' => '40'],
            ['data' => 'name', 'title' => 'NAMA', 'width' => '40'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Service_' . date('YmdHis');
    // }
}

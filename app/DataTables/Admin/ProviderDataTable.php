<?php

namespace App\DataTables\Admin;

use App\Models\Provider;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('is_active', request('filter_status'));
                }
                if (request()->has('filter_is_manual') AND request('filter_is_manual') <> null) {
                    $query->where('is_manual', request('filter_is_manual'));
                }
                if (request()->has('filter_is_auto_update') AND request('filter_is_auto_update') <> null) {
                    $query->where('is_auto_update', request('filter_is_auto_update'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->where('id', 'like', "%".request('search')."%")
                        ->orWhere('name', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('name', function ($query) {
                return strtoupper($query->name);
            })
            ->editColumn('api_balance', function ($query) {
                return 'Rp ' . currency($query->api_balance ?? 0);
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->editColumn('is_manual', 'admin.' .request()->segment(2). '.button.manual')
            ->editColumn('is_active', 'admin.' .request()->segment(2). '.button.active')
            ->editColumn('is_auto_update', 'admin.' .request()->segment(2). '.button.auto-update')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'is_manual', 'is_active', 'is_auto_update']);
    }

    public function query(Provider $model)
    {
        return $model->query();
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
                            d.filter_is_manual = $("#filter_is_manual option:selected").val();
                            d.filter_is_auto_update = $("#filter_is_auto_update option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns() {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50'],
            ['data' => 'name', 'title' => 'NAMA'],
            ['data' => 'api_balance', 'title' => 'BALANCE'],
            ['data' => 'is_manual', 'title' => 'MANUAL', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'is_auto_update', 'title' => 'AUTO UPDATE LAYANAN', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'is_active', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Provider_' . date('YmdHis');
    // }
}

<?php

namespace App\DataTables\Admin;

use App\Models\UserLevel;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class UserLevelDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query->where('name', 'like', "%".request('search')."%")
                        ->orWhere('price', 'like', "%".request('search')."%")
                        ->orWhere('get_balance', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('name', function ($query) {
                return strtoupper($query->name);
            })
            ->editColumn('price', function ($query) {
                return currency($query->price);
            })
            ->editColumn('get_balance', function ($query) {
                return currency($query->get_balance);
            })
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at ?? null);
            })
            ->editColumn('updated_at', function ($query) {
                return format_datetime($query->updated_at ?? null);
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'description']);
    }

    public function query(UserLevel $model)
    {
        return $model->newQuery();
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
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(5, 'asc');
    }

    protected function getColumns() {
        return [
            ['data' => 'DT_RowIndex', 'title' => 'NO', 'width' => '20', 'orderable' => false, 'searchable' => false],
            ['data' => 'name', 'title' => 'NAMA', 'width' => '40', 'orderable' => false, 'searchable' => false],
            ['data' => 'price', 'title' => 'HARGA', 'width' => '40', 'orderable' => false, 'searchable' => false],
            ['data' => 'get_balance', 'title' => 'SALDO', 'width' => '150', 'orderable' => false, 'searchable' => false],
            ['data' => 'created_at', 'title' => 'DIBUAT', 'width' => '150', 'orderable' => false, 'searchable' => false],
            ['data' => 'updated_at', 'title' => 'DIUBAH', 'width' => '150', 'orderable' => false, 'searchable' => false],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Service_' . date('YmdHis');
    // }
}

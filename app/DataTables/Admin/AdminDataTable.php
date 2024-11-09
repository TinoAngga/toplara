<?php

namespace App\DataTables\Admin;

use App\Models\Admin;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class AdminDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('is_active', request('filter_status'));
                }
                if (request()->has('filter_level') AND request('filter_level') <> null) {
                    $query->where('level', request('filter_level'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query->where('full_name', 'like', "%".request('search')."%")
                        ->orWhere('username', 'like', "%".request('search')."%")
                        ->orWhere('level', 'like', "%".request('search')."%")
                        ->orWhere('created_at', 'like', "%".request('search')."%")
                        ->orWhere('updated_at', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('level', function ($query) {
                return ucwords(str_replace('-', ' ', $query->level));
            })
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at ?? null);
            })
            ->editColumn('updated_at', function ($query) {
                return format_datetime($query->updated_at ?? null);
            })
            ->addColumn('status', 'admin.' .request()->segment(2). '.button.active')
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'status']);
    }

    public function query(Admin $model)
    {
        return $model->newQueryWithoutRelationships();
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
                            d.filter_status = $("select[name=filter_status] option:selected").val();
                            d.filter_level = $("select[name=filter_level] option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(4);
    }

    protected function getColumns() {
        return [
            ['data' => 'DT_RowIndex', 'title' => 'NO', 'width' => '20', 'orderable' => false, 'searchable' => false],
            ['data' => 'full_name', 'title' => 'NAMA', 'width' => '40'],
            ['data' => 'username', 'title' => 'USERNAME', 'width' => '40'],
            ['data' => 'level', 'title' => 'LEVEL', 'width' => '150'],
            ['data' => 'created_at', 'title' => 'DIBUAT', 'width' => '150'],
            ['data' => 'updated_at', 'title' => 'DIUBAH', 'width' => '150'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Service_' . date('YmdHis');
    // }
}

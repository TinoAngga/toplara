<?php

namespace App\DataTables\Admin;

use App\Models\Admin;
use App\Models\AdminLog;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class AdminLogDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_admin') AND request('filter_admin') <> null) {
                    $query->where('admin_id', request('filter_admin'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                            ->whereHas('admin', function($query) {
                                $query->where('username', 'like', "%".request('search')."%");
                            })
                            ->orWhere('ip_address', 'like', "%".request('search')."%")
                            ->orWhere('user_agent', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at ?? null);
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'status']);
    }

    public function query(AdminLog $model)
    {
        return $model
            ->with('admin:id,username')
            ->newQuery();
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
            ['data' => 'admin.username', 'title' => 'USERNAME', 'width' => '40'],
            ['data' => 'ip_address', 'title' => 'IP ADDRESS', 'width' => '40'],
            ['data' => 'user_agent', 'title' => 'UA', 'width' => '150'],
            ['data' => 'created_at', 'title' => 'DIBUAT', 'width' => '150'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Service_' . date('YmdHis');
    // }
}

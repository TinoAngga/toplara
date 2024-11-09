<?php

namespace App\DataTables\Admin;

use App\Models\Banner;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BannerDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->where('id', 'like', "%".request('search')."%")
                        ->orWhere('name', 'like', "%".request('search')."%")
                        ->orWhere('url', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('name', function ($query) {
                return strtoupper($query->name);
            })
            ->editColumn('url', function ($query) {
                return "<a href=\"$query->url\" target=\"_blank\">$query->url</a>";
            })
            ->editColumn('value', function ($query) {
                return "<img src=\"".asset(config('constants.options.asset_img_banner') . $query->value)."\" class=\"img-fluid\" height=\"200px\" width=\"200px\"></a>";
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'url', 'value']);
    }

    public function query(Banner $model)
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
                        ],
                    ])
                    ->dom('<bottom><"float-left"><"float-right">r<"row"<"col-sm-4"i><"col-sm-4"><"col-sm-4"p>>')
                    ->ajax([
                        'url' => url()->current(),
                        'data' => 'function(d) {
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns() {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50'],
            ['data' => 'name', 'title' => 'NAMA'],
            ['data' => 'url', 'title' => 'URL'],
            ['data' => 'value', 'title' => 'GAMBAR', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/Banner_' . date('YmdHis');
    // }
}

<?php

namespace App\DataTables\Primary;

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
            ->order(function ($query) {
                if (request()->has('order')) {
                    if (in_array(request('order')[0]['column'], ['2', '3', '4', '5'])) {
                        $query->orderBy('price->public', 'ASC');
                    }
                }
            })
            ->filter(function ($query) {
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('services.is_active', request('filter_status'));
                }
                if (request()->has('filter_category') AND request('filter_category') <> null) {
                    $query->where('service_category_id', request('filter_category'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->orWhereHas('category', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhere('services.id', 'like', "%".request('search')."%")
                        ->orWhere('services.name', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('price', function ($query) {
                return 'Rp ' . currency($query->price->public);
            })
            ->editColumn('price_silver', function ($query) {
                return 'Rp ' . currency($query->price->silver);
            })
            ->editColumn('price_gold', function ($query) {
                return 'Rp ' . currency($query->price->gold);
            })
            ->editColumn('price_vip', function ($query) {
                return 'Rp ' . currency($query->price->vip);
            })
            ->editColumn('status', function ($query) {
                return $query->is_active == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>';
            })
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'status', 'price', 'profit']);
    }

    public function query(Service $model)
    {
        return $model->with(['category'  => function ($q) {
            $q->orderBy('name', 'ASC');
            }])
            ->select('services.*')
            ->whereHas('category', function ($query) {
                return $query->where('is_active', 1);
            });
    }

    public function html() {
        return $this->builder()
                    ->setTableId('datatable')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        "responsive" => true,
                        "autoWidth" => false,
                        "pageLength" => 25,
                        "lengthMenu" => [5, 10, 25, 50, 100],
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
                        'columnDefs' => [
                            ['targets' => [1], 'visible' => false],
                        ],
                        'drawCallback' => 'function (settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: "current"
                            }).nodes();
                            var last = null;
                            api.column(1, {
                                page: "current"
                            }).data().each(function (group, i) {
                                if (last !== group) {
                                    $(rows).eq(i).before(
                                        `<tr class=\"group text-center font-weight-bold bg-primary\"><td colspan=\"6\" class=\"text-white \">${group}</td></tr>`
                                    );
                                    last = group;
                                }
                            });
                        }'
                    ])
                    ->dom('<bottom><"float-left"><"float-right">r<"row"<"col-sm-4"i><"col-sm-4"><"col-sm-4"p>>')
                    ->ajax([
                        'url' => url()->current(),
                        'data' => 'function(d) {
                            d.filter_status = $("#filter_status option:selected").val();
                            d.filter_category = $("#filter_category option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ]);
    }

    protected function getColumns() {
        return [
            ['data' => 'name', 'title' => 'LAYANAN', 'width' => '40', 'class' => 'align-middle text-center'],
            ['data' => 'category.name', 'title' => 'KATEGORI', 'width' => '40', 'class' => 'align-middle text-center'],
            ['data' => 'price', 'title' => 'PUBLIC', 'width' => '150', 'class' => 'align-middle text-center'],
            ['data' => 'price_silver', 'title' => 'SILVER', 'width' => '150', 'class' => 'align-middle text-center'],
            ['data' => 'price_gold', 'title' => 'GOLD', 'width' => '150', 'class' => 'align-middle text-center'],
            ['data' => 'price_vip', 'title' => 'VIP', 'width' => '150', 'class' => 'align-middle text-center'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }
}

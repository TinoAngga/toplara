<?php

namespace App\DataTables\Admin;

use App\Models\ProviderApiLog;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class ProviderApiLogDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_provider') AND request('filter_provider') <> null) {
                    $query->where('provider_id', request('filter_provider'));
                }
                if (request()->has('filter_order') AND request('filter_order') <> null) {
                    $query->whereHas('order', function($query) {
                        $query->where('invoice', 'like', "%".request('filter_order')."%");
                    });
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->whereHas('provider', function($query) {
                            $query->where('id', 'like', "%".request('search')."%")
                            ->orWhere('name', 'like', "%".request('search')."%");
                        })
                        ->orWhereHas('order', function($query) {
                            $query->where('id', 'like', "%".request('search')."%")
                            ->orWhere('invoice', 'like', "%".request('search')."%")
                            ->orWhere('data', 'like', "%".request('search')."%");
                        })
                        ->orWhere('id', 'like', "%".request('search')."%")
                        ->orWhere('order_id', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('provider.name',  function($query) {
                return strtoupper($query->provider->name);
            })
            ->editColumn('order.invoice', function($query) {
                return "
                    <a href=\"javascript:;\"
                        onclick=\"modal('detail', '#$query->id', '".route('admin.order.show', $query->order_id)."')\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Detail\">
                        ".$query->order->invoice ?? ''."
                    </a>";
            })
            ->editColumn('description', function ($query) {
                return "<a href=\"javascript:;\" onclick=\"info('".addslashes(strip_tags($query->description))."')\" data-toggle=\"tooltip\" title=\"Detail\">".\Str::limit($query->description, 30, '...')."</a>";
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->rawColumns(['action', 'order.invoice', 'description']);
    }

    public function query(ProviderApiLog $model)
    {
        return $model->query()->with([
            'order' => function ($query) {
                $query->select('id', 'invoice');
            },
            'provider' => function ($query) {
                $query->select('id', 'name');
            }
        ]);
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
                            d.filter_provider = $("#filter_provider option:selected").val();
                            d.filter_order = $("#filter_order").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(1);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'DT_RowIndex', 'title' => 'NO', 'width' => '20', 'orderable' => false, 'searchable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'WAKTU', 'width' => '100'],
            ['data' => 'order.invoice', 'name' => 'order.invoice', 'title' => 'ORDER INVOICE', 'width' => '100', 'orderable' => false],
            ['data' => 'provider.name', 'name' => 'provider.name', 'title' => 'PROVIDER', 'width' => '100', 'orderable' => false],
            ['data' => 'description', 'name' => 'description', 'title' => 'DESKRIPSI', 'width' => '100', 'orderable' => false],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin\ApiLogProvider_' . date('YmdHis');
    // }
}

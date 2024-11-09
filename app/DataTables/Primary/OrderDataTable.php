<?php

namespace App\DataTables\Primary;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class OrderDataTable extends DataTable
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
                if (request()->has('filter_service') AND request('filter_service') <> null) {
                    $query->where('service_id', (int) request('filter_service'));
                }
                if (request()->has('filter_payment') AND request('filter_payment') <> null) {
                    $query->where('payment_id', (int) request('filter_payment'));
                }
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('status', request('filter_status'));
                }
                if (request()->has('filter_paid') AND request('filter_paid') <> null) {
                    $query->where('is_paid', (int) request('filter_paid'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->whereHas('service', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhereHas('payment', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhere('id', 'like', "%".request('search')."%")
                        ->orWhere('invoice', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('invoice',  function($query) {
                return "<h5><a href=\"" .route('order.invoice', $query->invoice). "\" class=\"btn btn-primary text-white\"><i class=\"mdi mdi-file-document\"></i>$query->invoice</a></h5>";
            })
            ->editColumn('service_id',  function($query) {
                return strtoupper($query->service->name) . ' ('.$query->service->category->name.')';
            })
            ->editColumn('data',  function($query) {
                return  (!is_null($query->additional_data)) ? $query->data . '('.$query->additional_data.')' : $query->data;
            })
            ->editColumn('price', function ($query) {
                return 'Rp ' . currency($query->price);
            })
            ->editColumn('is_refund', function ($query) {
                return isRefund($query->is_refund);
            })
            ->editColumn('is_paid', function ($query) {
                return isPaid($query->is_paid);
            })
            ->editColumn('status', function ($query) {
                return badgeStatus($query->status, $query->is_paid);
            })
            ->rawColumns(['status', 'is_paid', 'invoice', 'data', 'additional_data']);
    }

    public function query(Order $model)
    {
        return $model->newQuery()
            ->with('service:id,name,service_category_id')
            ->where('user_id', Auth::id());
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
                            d.filter_service = $("#filter_service option:selected").val();
                            d.filter_payment = $("#filter_payment option:selected").val();
                            d.filter_status = $("#filter_status option:selected").val();
                            d.filter_paid = $("#filter_paid option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(1);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'invoice', 'name' => 'invoice', 'title' => 'INVOICE', 'width' => '100'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'WAKTU', 'width' => '100'],
            ['data' => 'service_id', 'name' => 'service.name', 'title' => 'LAYANAN', 'width' => '40'],
            ['data' => 'data', 'title' => 'DATA', 'width' => '100', 'orderable' => false, 'searchable' => false, 'width' => '100'],
            ['data' => 'price', 'title' => 'HARGA', 'width' => '40'],
            ['data' => 'is_paid', 'title' => 'LUNAS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }
}

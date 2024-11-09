<?php

namespace App\DataTables\Primary;

use App\Models\Deposit;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class DepositDataTable extends DataTable
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
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('status', request('filter_status'));
                }
                if (request()->has('filter_paid') AND request('filter_paid') <> null) {
                    $query->where('is_paid', request('filter_paid'));
                }
                if (request()->has('filter_payment') AND request('filter_payment') <> null) {
                    $query->whereHas('payment', function($query){
                        $query->where('id', request('filter_payment'));
                    });
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->whereHas('payment', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhere('id', 'like', "%".request('search')."%")
                        ->orWhere('invoice', 'like', "%".request('search')."%")
                        ->orWhere('amount', 'like', "%".request('search')."%")
                        ->orWhere('balance', 'like', "%".request('search')."%")
                        ->orWhere('unique_code', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('invoice',  function($query) {
                return "<a href=\"" .route('deposit.invoice', $query->invoice). "\" class=\"btn btn-primary btn-sm\"><i class=\"mdi mdi-file-document-box-multiple-outline\"></i>$query->invoice</a>";
            })
            ->editColumn('payment.name',  function($query) {
                return strtoupper($query->payment->name);
            })
            ->editColumn('amount', function ($query) {
                return 'Rp ' . currency($query->amount);
            })
            ->editColumn('balance', function ($query) {
                return 'Rp ' . currency($query->balance);
            })
            ->editColumn('status', function ($query) {
                return badgeStatus($query->status, $query->is_paid);
            })
            ->editColumn('is_paid', function ($query) {
                return isPaid($query->is_paid);
            })
            ->rawColumns(['invoice', 'detail', 'status', 'is_paid']);
    }

    public function query(Deposit $model)
    {
        return $model->newQuery()->with('payment')->where('user_id', Auth::user()->id);
    }

    public function html() {
        return $this->builder()
                    ->setTableId('datatable')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        "responsive" => true,
                        "autoWidth" => true,
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
            ['data' => 'invoice', 'title' => 'INVOICE', 'width' => '100px'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'WAKTU', 'width' => '100'],
            ['data' => 'payment.name', 'title' => 'PEMBAYARAN', 'width' => '40'],
            ['data' => 'amount', 'title' => 'NOMINAL', 'width' => '40'],
            ['data' => 'balance', 'title' => 'SALDO', 'width' => '50'],
            ['data' => 'is_paid', 'title' => 'LUNAS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }
}

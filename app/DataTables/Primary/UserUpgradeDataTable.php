<?php

namespace App\DataTables\Primary;

use App\Models\UserUpgrade;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class UserUpgradeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_payment') AND request('filter_payment') <> null) {
                    $query->where('payment_id', request('filter_payment'));
                }
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('status', request('filter_status'));
                }
                if (request()->has('filter_paid') AND request('filter_paid') <> null) {
                    $query->where('is_paid', request('filter_paid'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->whereHas('payment', function($query) {
                            $query->where('name', 'like', "%".request('search')."%");
                        })
                        ->orWhereHas('user', function($query) {
                            $query->orWhere('username', 'like', "%".request('search')."%")
                            ->orWhere('full_name', 'like', "%".request('search')."%");
                        })
                        ->orWhere('id', 'like', "%".request('search')."%")
                        ->orWhere('invoice', 'like', "%".request('search')."%")
                        ->orWhere('level', 'like', "%".request('search')."%")
                        ->orWhere('price', 'like', "%".request('search')."%")
                        ->orWhere('ip_address', 'like', "%".request('search')."%");
                    });
                }
            })
            // ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('invoice',  function($query) {
                return "<h5><a href=\"" .route('account.upgrade.invoice', $query->invoice). "\" class=\"badge badge-primary badge-lg text-white\"><i class=\"mdi mdi-file-document-box-multiple-outline mr-2\"></i>$query->invoice</a></h5>";
            })
            ->editColumn('payment_id',  function($query) {
                return strtoupper($query->payment->name);
            })
            ->editColumn('level',  function($query) {
                return strtoupper($query->level);
            })
            ->editColumn('price',  function($query) {
                return 'Rp ' . currency($query->price);
            })
            ->editColumn('is_paid', function ($query) {
                return isPaid($query->is_paid);
            })
            ->editColumn('status', function ($query) {
                return badgeStatus($query->status);
            })
            ->rawColumns(['status', 'is_paid', 'invoice']);
    }

    public function query(UserUpgrade $model)
    {
        return $model->query()->with([
            'user' => function ($query) {
                $query->select('id', 'username', 'level');
            },
            'payment' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('user_id', Auth::user()->id);
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
                            d.filter_payment = $("#filter_payment option:selected").val();
                            d.filter_status = $("#filter_status option:selected").val();
                            d.filter_paid = $("#filter_paid option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'WAKTU', 'width' => '100'],
            ['data' => 'invoice', 'name' => 'invoice', 'title' => 'INVOICE', 'width' => '100', 'orderable' => false],
            ['data' => 'payment_id', 'name' => 'payment.name', 'title' => 'PEMBAYARAN', 'width' => '40'],
            ['data' => 'level', 'title' => 'LEVEL', 'width' => '40'],
            ['data' => 'price', 'title' => 'HARGA', 'width' => '40'],
            ['data' => 'is_paid', 'title' => 'LUNAS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }

    protected function filename(): String
    {
        return 'Admin\UserUpgrade_' . date('YmdHis');
    }
}

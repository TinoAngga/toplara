<?php

namespace App\DataTables\Admin;

use App\Models\PaymentMethod;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PaymentMethodDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('is_active', request('filter_status'));
                }
                if (request()->has('filter_type') AND request('filter_type') <> null) {
                    $query->where('type', request('filter_type'));
                }
                if (request()->has('filter_is_public') AND request('filter_is_public') <> null) {
                    $query->where('is_public', request('filter_is_public'));
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->where('id', 'like', "%".request('search')."%")
                        ->orWhere('name', 'like', "%".request('search')."%")
                        ->orWhere('type', 'like', "%".request('search')."%")
                        ->orWhere('fee', 'like', "%".request('search')."%")
                        ->orWhere('fee_percent', 'like', "%".request('search')."%")
                        ->orWhere('payment_gateway', 'like', "%".request('search')."%")
                        ->orWhere('time_used', 'like', "%".request('search')."%")
                        ->orWhere('time_stopped', 'like', "%".request('search')."%");
                    });
                }
            })
            ->editColumn('type', function ($query) {
                return strtoupper(str_replace('_', ' ', $query->type));
            })
            ->editColumn('name', function ($query) {
                return strtoupper($query->name);
            })
            ->editColumn('fee', function ($query) {
                return 'FEE : Rp ' . currency($query->fee) . '<br /> FEE PERSEN ' . $query->fee_percent . '%';
            })
            ->editColumn('payment_gateway', function ($query) {
                return !is_null($query->payment_gateway) ? strtoupper($query->payment_gateway) . '<br />(' . $query->payment_gateway_code . ')' : '-';
            })
            ->addColumn('manual', 'admin.' .request()->segment(2). '.button.manual')
            ->addColumn('public', 'admin.' .request()->segment(2). '.button.public')
            ->addColumn('status', 'admin.' .request()->segment(2). '.button.active')
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['action', 'status', 'manual', 'public', 'fee', 'payment_gateway']);
    }

    public function query(PaymentMethod $model)
    {
        return $model->query()->select('payment_methods.*');
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
                            d.filter_type = $("#filter_type option:selected").val();
                            d.filter_is_public = $("#filter_is_public option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns() {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50'],
            ['data' => 'type', 'title' => 'TIPE'],
            ['data' => 'name', 'title' => 'NAMA'],
            ['data' => 'fee', 'title' => 'FEE'],
            ['data' => 'payment_gateway', 'title' => 'PAYMENT GATEWAY'],
            ['data' => 'public', 'title' => 'PUBLIK', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'manual', 'title' => 'MANUAL', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '100'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin/PaymentMethod' . date('YmdHis');
    // }
}

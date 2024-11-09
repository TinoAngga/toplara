<?php

namespace App\DataTables\Admin;

use App\Models\UserUpgrade;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class UserUpgradeDataTable extends DataTable
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
                if (request()->has('filter_user') AND request('filter_user') <> null) {
                    $query->where('user_id', request('filter_user'));
                }
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
                return clipboardCopy($query->invoice, 'data-invoice-'.$query->id);
            })
            ->editColumn('user_id',  function($query) {
                return $query->user->username;
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
            ->editColumn('fee',  function($query) {
                return 'Rp ' . currency($query->fee);
            })
            ->editColumn('unique_code',  function($query) {
                return 'Rp ' . currency($query->unique_code);
            })
            ->editColumn('is_paid', function ($query) {
                return isPaidAdmin($query->id, $query->invoice, $query->is_paid, $query->status);
            })
            ->editColumn('status', function ($query) {
                $status = [
                    'pending' => ['name' => 'Pending', 'color' => 'warning'],
                    'sukses' => ['name' => 'Sukses', 'color' => 'success'],
                    'gagal' => ['name' => 'Gagal', 'color' => 'danger'],
                    'kadaluarsa' => ['name' => 'Kadaluarsa', 'color' => 'danger'],
                ];
                $html = "<div class=\"btn-group\">
                        <button type=\"button\" class=\"btn btn-sm btn-".$status[$query->status]['color']." dropdown-toggle font-weight-bold\" data-toggle=\"dropdown\" aria-expanded=\"false\"> ".strtoupper($query->status)." <span class=\"caret\"></span> </button>
                            <div class=\"dropdown-menu\">";
                        foreach ($status as $key => $value) {
                            $html .= "<a  class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"orderChangeStatus('" . url('admin/user-upgrade/'.$query->id.'/status/' . $key) . "', '".$query->invoice."')\">
                            ".$value['name']."
                            </a>";
                        }
                $html .= "</div></div>";
                return $html;
            })
            ->addColumn('action', 'admin.' .request()->segment(2). '.button.action')
            ->rawColumns(['action', 'status', 'is_paid', 'invoice']);
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
                            d.filter_user = $("#filter_user option:selected").val();
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
            ['data' => 'user_id', 'name' => 'user.username', 'title' => 'USERNAME', 'width' => '100', 'orderable' => false],
            ['data' => 'payment_id', 'name' => 'payment.name', 'title' => 'PEMBAYARAN', 'width' => '40'],
            ['data' => 'level', 'title' => 'LEVEL', 'width' => '40'],
            ['data' => 'price', 'title' => 'HARGA', 'width' => '40'],
            ['data' => 'fee', 'title' => 'FEE', 'width' => '40'],
            ['data' => 'unique_code', 'title' => 'KODE UNIK', 'width' => '40'],
            ['data' => 'is_paid', 'title' => 'LUNAS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }

    // protected function filename()
    // {
    //     return 'Admin\UserUpgrade_' . date('YmdHis');
    // }
}

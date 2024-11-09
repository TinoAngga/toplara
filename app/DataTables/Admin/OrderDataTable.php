<?php

namespace App\DataTables\Admin;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class OrderDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                $filters = [
                    'filter_user' => 'user_id',
                    'filter_service' => 'service_id',
                    'filter_provider' => 'provider_id',
                    'filter_payment' => 'payment_id',
                    'filter_order_type' => 'order_type',
                    'filter_status' => 'status',
                    'filter_paid' => 'is_paid'
                ];

                foreach ($filters as $requestKey => $column) {
                    if (request()->has($requestKey) && request($requestKey) !== null) {
                        $query->where($column, request($requestKey));
                    }
                }

                if (request()->has('search') && request('search') !== null) {
                    $searchTerm = request('search');
                    $query->where(function($query) use ($searchTerm) {
                        $query->whereHas('service', function($query) use ($searchTerm) {
                            $query->where('name', 'like', "%$searchTerm%");
                        })
                        ->orWhereHas('payment', function($query) use ($searchTerm) {
                            $query->where('name', 'like', "%$searchTerm%");
                        })
                        ->orWhereHas('provider', function($query) use ($searchTerm) {
                            $query->where('name', 'like', "%$searchTerm%");
                        })
                        ->orWhereHas('user', function($query) use ($searchTerm) {
                            $query->where('username', 'like', "%$searchTerm%")
                                  ->orWhere('full_name', 'like', "%$searchTerm%");
                        })
                        ->orWhere('id', 'like', "%$searchTerm%")
                        ->orWhere('invoice', 'like', "%$searchTerm%")
                        ->orWhere('data', 'like', "%$searchTerm%")
                        ->orWhere('additional_data', 'like', "%$searchTerm%")
                        ->orWhere('provider_order_id', 'like', "%$searchTerm%")
                        ->orWhere('whatsapp_order', 'like', "%$searchTerm%")
                        ->orWhere('provider_order_description', 'like', "%$searchTerm%");
                    });
                }
            })
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('invoice', function($query) {
                return clipboardCopy($query->invoice, 'data-invoice-'.$query->id);
            })
            ->editColumn('user_id', function($query) {
                return $query->user->username;
            })
            ->editColumn('payment_id', function($query) {
                return strtoupper($query->payment->name);
            })
            ->editColumn('service_id', function($query) {
                $color = ($query->service_id >= 554 && $query->service_id <= 589) || in_array($query->service_id, [645, 646, 647]) ? 'text-danger' : '';
                return '<div class="'.$color.'"><strong>'.strtoupper($query->service->name).' ('.$query->service->category->name.')</strong></div>';
            })
            ->editColumn('provider_id', function($query) {
                return strtoupper($query->provider->name);
            })
            ->editColumn('provider_order_id', function($query) {
                return $query->provider_order_id ? clipboardCopy($query->provider_order_id, 'data-provider-order-id-'.$query->provider_order_id) : '-';
            })
            ->editColumn('whatsapp_order', function($query) {
                return $query->whatsapp_order ? '<a href="https://wa.me/' . $query->whatsapp_order . '" target="_blank">' . clipboardCopy('https://wa.me/' . $query->whatsapp_order, 'whatsapp_order' . $query->id) . '</a>' : '-';
            })
            ->editColumn('data', function($query) {
                return clipboardCopy($query->data, 'data-data-'.$query->id);
            })
            ->editColumn('additional_data', function($query) {
                return clipboardCopy($query->additional_data ?? '-', 'data-additional-data-'.$query->id);
            })
            ->editColumn('price', function ($query) {
                return 'Rp ' . currency($query->price);
            })
            ->editColumn('profit', function ($query) {
                return 'Rp ' . currency($query->profit);
            })
            ->editColumn('is_paid', function ($query) {
                return isPaidAdmin($query->id, $query->invoice, $query->is_paid, $query->status);
            })
            ->editColumn('status', function ($query) {
    $status = [
        'pending' => ['name' => 'Pending', 'color' => 'warning'],
        'proses' => ['name' => 'Proses', 'color' => 'info'],
        'sukses' => ['name' => 'Sukses', 'color' => 'success'],
        'gagal' => ['name' => 'Gagal', 'color' => 'danger'],
        // 'kadaluarsa' => ['name' => 'Kadaluarsa', 'color' => 'danger'],
        'SALAH ID' => ['name' => 'SALAH ID', 'color' => 'danger'],
    ];

    $html = "<div class=\"btn-group\">
            <button type=\"button\" class=\"btn btn-sm btn-".$status[$query->status]['color']." dropdown-toggle font-weight-bold\" data-toggle=\"dropdown\" aria-expanded=\"false\"> ".strtoupper($query->status)." <span class=\"caret\"></span> </button>
            <div class=\"dropdown-menu\">";
    
    foreach ($status as $key => $value) {
        $html .= "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"orderChangeStatus('" . url('admin/order/'.$query->id.'/status/' . $key) . "', '".$query->invoice."', '$key')\">
                ".$value['name']."
                </a>";
    }

    $html .= "</div></div>";

    return $html;
})

            ->addColumn('action', 'admin.' . request()->segment(2) . '.button.action')
            ->setRowClass(function ($query) {
                return $query->is_refund == 1 ? 'table-danger' : ($query->is_refund == 0 ? 'table-success' : 'table-secondary');
            })
            ->rawColumns(['action', 'status', 'is_paid', 'invoice', 'whatsapp_order', 'data', 'additional_data', 'provider_order_id', 'service_id']);
    }

    public function query(Order $model)
    {
        return $model->with([
            'user:id,username,level,full_name,email',
            'payment:id,name',
            'service:id,service_category_id,name',
            'provider:id,name'
        ])->select('orders.*');
    }

    public function html()
    {
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
                    "search" => "Cari:",
                    "paginate" => [
                        "first" => "Pertama",
                        "previous" => "<i class='mdi mdi-chevron-left'>",
                        "next" => "<i class='mdi mdi-chevron-right'>",
                        "last" => "Terakhir"
                    ],
                ]
            ])
            ->dom('<bottom><"float-left"><"float-right">r<"row"<"col-sm-4"i><"col-sm-4"><"col-sm-4"p>>')
            ->ajax([
                'url' => url()->current(),
                'data' => 'function(d) {
                    d.filter_user = $("#filter_user option:selected").val();
                    d.filter_service = $("#filter_service option:selected").val();
                    d.filter_provider = $("#filter_provider option:selected").val();
                    d.filter_payment = $("#filter_payment option:selected").val();
                    d.filter_order_type = $("#filter_order_type option:selected").val();
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
            ['data' => 'service_id', 'name' => 'service.name', 'title' => 'LAYANAN', 'width' => '40'],
            ['data' => 'provider_id', 'name' => 'provider.name', 'title' => 'PROVIDER', 'width' => '40'],
            ['data' => 'provider_order_id', 'title' => 'POID', 'class' => 'text-center', 'orderable' => false, 'width' => '50'],
            ['data' => 'whatsapp_order', 'title' => 'Whatsapp', 'orderable' => false, 'width' => '50'],
            ['data' => 'data', 'title' => 'DATA', 'width' => '100', 'orderable' => false],
            ['data' => 'additional_data', 'title' => 'DATA TAMBAHAN', 'orderable' => false, 'width' => '100'],
            ['data' => 'price', 'title' => 'HARGA', 'width' => '40'],
            ['data' => 'profit', 'title' => 'PROFIT', 'width' => '50'],
            ['data' => 'is_paid', 'title' => 'LUNAS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'status', 'title' => 'STATUS', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
            ['data' => 'action', 'title' => 'AKSI', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '50'],
        ];
    }
}

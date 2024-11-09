<?php

namespace App\DataTables\Primary;

use App\Models\BalanceMutation;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class BalanceMutationDataTable extends DataTable
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
                if (request()->has('filter_start_date') AND request('filter_start_date') <> null AND request()->has('filter_end_date') AND request('filter_end_date') <> null) {
                    $query->whereBetween('created_at', [request('filter_start_date'), request('filter_end_date')]);
                }
                if (request()->has('filter_type')) {
                    $query->where('type', 'like', "%".request('filter_type')."%");
                }
                if (request()->has('filter_category')) {
                    $query->where('category', 'like', "%".request('filter_category')."%");
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->where('id', 'like', "%".request('search')."%")
                        ->orWhere('amount', 'like', "%".request('search')."%")
                        ->orWhere('description', 'like', "%".request('search')."%")
                        ->orWhere('beginning_balance', 'like', "%".request('search')."%")
                        ->orWhere('last_balance', 'like', "%".request('search')."%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('created_at', function ($query) {
                return format_datetime($query->created_at);
            })
            ->editColumn('description', function ($query) {
                return "<a href=\"javascript:;\" onclick=\"info('$query->description')\" data-toggle=\"tooltip\" title=\"Detail\">".\Str::limit($query->description, 30, '...')."</a>";
            })
            ->editColumn('type',  function($query) {
                return strtoupper($query->type);
            })
            ->editColumn('category',  function($query) {
                return strtoupper($query->category);
            })
            ->editColumn('amount', function ($query) {
                return 'Rp ' . currency($query->amount);
            })
            ->editColumn('beginning_balance', function ($query) {
                return 'Rp ' . currency($query->beginning_balance);
            })
            ->editColumn('last_balance', function ($query) {
                return 'Rp ' . currency($query->last_balance);
            })
            ->setRowId('id')
            ->setRowClass(function ($query) {
                if ($query->type == 'debit') {
                    return 'table-success';
                } elseif ($query->type == 'credit') {
                    return 'table-danger';
                } else {
                    return 'table-secondary';
                }
            })
            ->rawColumns(['description']);
    }

    public function query(BalanceMutation $model)
    {
        return $model->newQuery()->where('user_id', Auth::user()->id);
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
                            d.filter_start_date = $("input[name=filter_start_date]").val();
                            d.filter_end_date = $("input[name=filter_end_date").val();
                            d.filter_type = $("#filter_type option:selected").val();
                            d.filter_category = $("#filter_category option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(1, 'desc');
    }

    protected function getColumns()
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'NO', 'width' => '20', 'orderable' => false, 'searchable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'WAKTU', 'width' => '100'],
            ['data' => 'type', 'title' => 'TIPE', 'width' => '40'],
            ['data' => 'category', 'title' => 'KATEGORI', 'width' => '40'],
            ['data' => 'description', 'title' => 'DESKRIPSI', 'width' => '40'],
            ['data' => 'amount', 'title' => 'NOMINAL', 'width' => '40'],
            ['data' => 'beginning_balance', 'title' => 'SEBELUM', 'width' => '50'],
            ['data' => 'last_balance', 'title' => 'SESUDAH', 'width' => '50'],
        ];
    }

}

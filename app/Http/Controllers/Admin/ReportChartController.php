<?php

namespace App\Http\Controllers\Admin;

use App\Charts\Admin\DepositChartLine;
use App\Charts\Admin\DepositChartPie;
use App\Charts\Admin\OrderChartLine;
use App\Charts\Admin\OrderChartPie;
use App\Charts\Admin\UserUpgradeChartLine;
use App\Charts\Admin\UserUpgradeChartPie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportChartController extends Controller
{
    public function index(){

    }

    public function order(Request $request,
        OrderChartLine $orderChartLine,
        OrderChartPie $orderChartPie
    ){
        if (isXMLRequest() AND $request->hasAny(['start_date', 'end_date', 'order', 'type'])) {
            $startDate = $request->start_date ?? 'Y-m-01';
            $endDate = $request->end_date ?? 'Y-m-t';
            $order = $request->order;
            $chart_item = list_date_range($startDate, $endDate);
            $chart = [];
            foreach ($chart_item as $key => $value) {
                array_push($chart, [
                    'y' => $value,
                    'all' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        }),
                    'pending' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                        ->where('status', 'pending')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        }),
                    'proses' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                         ->where('status', 'proses')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        }),
                    'sukses' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                         ->where('status', 'sukses')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        }),
                    'gagal' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                         ->where('status', 'gagal')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        }),
                    'kadaluarsa' => DB::table('orders')
                        ->select(DB::raw('COUNT(id) AS count'))
                         ->where('status', 'kadaluarsa')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($order, function ($query, $order) {
                            return $query->where('order_type', $order);
                        })
                ]);
            }
            return response()->json($chart);
        }
        $page = 'Grafik Laporan Pesanan';
        return view('admin.report-chart.order', [
            'page' => $page,
            'orderChartLine' => $orderChartLine->build($request),
            'orderChartPie' => $orderChartPie->build($request)
        ]);
    }

    public function deposit(Request $request,
        DepositChartLine $depositChartLine,
        DepositChartPie $depositChartPie
    ){
        $page = 'Grafik Laporan Deposit';
        return view('admin.report-chart.deposit', [
            'page' => $page,
            'depositChartLine' => $depositChartLine->build($request),
            'depositChartPie' => $depositChartPie->build($request)
        ]);
    }

    public function upgradeLevel(Request $request,
        UserUpgradeChartLine $userUpgradeChartLine,
        UserUpgradeChartPie $userUpgradeChartPie
    ){
        $page = 'Grafik Laporan Peningkatan Level';
        return view('admin.report-chart.upgrade-level', [
            'page' => $page,
            'userUpgradeChartLine' => $userUpgradeChartLine->build($request),
            'userUpgradeChartPie' => $userUpgradeChartPie->build($request)
        ]);
    }

}

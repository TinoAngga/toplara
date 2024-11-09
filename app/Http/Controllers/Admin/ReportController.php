<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (isXMLRequest() AND $request->has('type') AND $request->type <> null) return $this->getReportIndex($request->type);
        $page = 'Laporan';
        return view('admin.report.index', compact('page'));
    }

    protected function getReportIndex($type = '')
    {
        if ($type == 'order') {
            $data = [
                'all' => [
                    'count' => currency(DB::table('orders')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->sum('price')),
                ],
                'pending' => [
                    'count' => currency(DB::table('orders')->where('status', 'pending')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->where('status', 'pending')->sum('price')),
                ],
                'proses' => [
                    'count' => currency(DB::table('orders')->where('status', 'proses')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->where('status', 'proses')->sum('price')),
                ],
                'sukses' => [
                    'count' => currency(DB::table('orders')->where('status', 'sukses')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->where('status', 'sukses')->sum('price')),
                ],
                'gagal' => [
                    'count' => currency(DB::table('orders')->where('status', 'gagal')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->where('status', 'gagal')->sum('price')),
                ],
                'kadaluarsa' => [
                    'count' => currency(DB::table('orders')->where('status', 'kadaluarsa')->count()),
                    'sum' => 'Rp ' . currency(DB::table('orders')->where('status', 'kadaluarsa')->sum('price')),
                ],
            ];
        } else if ($type == 'deposit') {
            $data = [
                'all' => [
                    'count' => currency(DB::table('deposits')->count()),
                    'sum' => 'Rp ' . currency(DB::table('deposits')->sum('amount')),
                ],
                'pending' => [
                    'count' => currency(DB::table('deposits')->where('status', 'pending')->count()),
                    'sum' => 'Rp ' . currency(DB::table('deposits')->where('status', 'pending')->sum('amount')),
                ],
                'sukses' => [
                    'count' => currency(DB::table('deposits')->where('status', 'sukses')->count()),
                    'sum' => 'Rp ' . currency(DB::table('deposits')->where('status', 'sukses')->sum('amount')),
                ],
                'gagal' => [
                    'count' => currency(DB::table('deposits')->where('status', 'gagal')->count()),
                    'sum' => 'Rp ' . currency(DB::table('deposits')->where('status', 'gagal')->sum('amount')),
                ],
                'kadaluarsa' => [
                    'count' => currency(DB::table('deposits')->where('status', 'kadaluarsa')->count()),
                    'sum' => 'Rp ' . currency(DB::table('deposits')->where('status', 'kadaluarsa')->sum('amount')),
                ],
            ];
        } else if ($type == 'upgrade-level') {
            $data = [
                'all' => [
                    'count' => currency(DB::table('user_upgrades')->count()),
                    'sum' => 'Rp ' . currency(DB::table('user_upgrades')->sum('price')),
                ],
                'pending' => [
                    'count' => currency(DB::table('user_upgrades')->where('status', 'pending')->count()),
                    'sum' => 'Rp ' . currency(DB::table('user_upgrades')->where('status', 'pending')->sum('price')),
                ],
                'sukses' => [
                    'count' => currency(DB::table('user_upgrades')->where('status', 'sukses')->count()),
                    'sum' => 'Rp ' . currency(DB::table('user_upgrades')->where('status', 'sukses')->sum('price')),
                ],
                'gagal' => [
                    'count' => currency(DB::table('user_upgrades')->where('status', 'gagal')->count()),
                    'sum' => 'Rp ' . currency(DB::table('user_upgrades')->where('status', 'gagal')->sum('price')),
                ],
                'kadaluarsa' => [
                    'count' => currency(DB::table('user_upgrades')->where('status', 'kadaluarsa')->count()),
                    'sum' => 'Rp ' . currency(DB::table('user_upgrades')->where('status', 'kadaluarsa')->sum('price')),
                ],
            ];
        }
        return response()->json($data);
    }

    public function order(Request $request)
    {
        if (isXMLRequest()) {
            if ($request->hasAny(['start_date', 'end_date', 'type']) OR $request->widget == 1) {
                $startDate = $request->start_date ?? date('Y-m-01');
                $endDate = $request->end_date ?? date('Y-m-t');
                $type = $request->type;
                $data['all'] = DB::table('orders')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->first();
                $data['gross'] = DB::table('orders')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'sukses')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['net'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'sukses')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['pending'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'pending')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['proses'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'proses')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['sukses'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'sukses')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['gagal'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'gagal')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['kadaluarsa'] = DB::table('orders')
                    ->select(DB::raw('SUM(profit) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'kadaluarsa')
                    ->when($type, function ($query, $type) {
                        return $query->where('order_type', $type);
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                return response()->json($data);
            }
        }
        $page = 'Laporan Pesanan';
        return view('admin.report.order', compact('page'));
    }

    public function deposit(Request $request)
    {
        if (isXMLRequest()) {
            if ($request->hasAny(['start_date', 'end_date', 'widget']) OR $request->widget == 1) {
                $startDate = $request->start_date ?? date('Y-m-01');
                $endDate = $request->end_date ?? date('Y-m-t');
                $data['all'] = DB::table('deposits')
                    ->select(DB::raw('SUM(amount) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['pending'] = DB::table('deposits')
                    ->select(DB::raw('SUM(amount) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'pending')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['sukses'] = DB::table('deposits')
                    ->select(DB::raw('SUM(amount) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'sukses')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['gagal'] = DB::table('deposits')
                    ->select(DB::raw('SUM(amount) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'gagal')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['kadaluarsa'] = DB::table('deposits')
                    ->select(DB::raw('SUM(amount) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'kadaluarsa')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                return response()->json($data);
            }
        }
        $page = 'Laporan Deposit';
        return view('admin.report.deposit', compact('page'));
    }

    public function upgradeLevel(Request $request)
    {
        if (isXMLRequest()) {
            if ($request->hasAny(['start_date', 'end_date', 'widget']) OR $request->widget == 1) {
                $startDate = $request->start_date ?? date('Y-m-01');
                $endDate = $request->end_date ?? date('Y-m-t');
                $data['all'] = DB::table('user_upgrades')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['pending'] = DB::table('user_upgrades')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'pending')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['sukses'] = DB::table('user_upgrades')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'sukses')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['gagal'] = DB::table('user_upgrades')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'gagal')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                $data['status']['kadaluarsa'] = DB::table('user_upgrades')
                    ->select(DB::raw('SUM(price) AS sum'), DB::raw('COUNT(id) AS count'))
                    ->where('status', 'kadaluarsa')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->first();
                return response()->json($data);
            }
        }
        $page = 'Laporan Peningkatan Level Pengguna';
        return view('admin.report.upgrade-level', compact('page'));
    }
}

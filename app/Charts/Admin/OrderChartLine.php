<?php

namespace App\Charts\Admin;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
class OrderChartLine
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($request): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');
        $type = $request->type;
        $chart_item = list_date_range($startDate, $endDate);
        $chart = [];
        foreach ($chart_item as $key => $value) {
            $chart['date'][] = format_date($value);
            $chart['all'][] = \DB::table('orders')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
            $chart['pending'][] = \DB::table('orders')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->where('status', 'pending')
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
            $chart['proses'][] = \DB::table('orders')
                            ->where('status', 'proses')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
            $chart['sukses'][] = \DB::table('orders')
                            ->where('status', 'sukses')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
            $chart['gagal'][] = \DB::table('orders')
                            ->where('status', 'gagal')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
            $chart['kadaluarsa'][] = \DB::table('orders')
                            ->where('status', 'kadaluarsa')
                            ->select(\DB::raw('COUNT(id) AS count'))
                            ->whereDate('created_at', $value)
                            ->when($type, function ($query) use ($type) {
                                return $query->where('order_type', $type);
                            })
                            ->first()->count;
        }
        return $this->chart->lineChart()
            ->addData('SEMUA', $chart['all'])
            ->addData('PENDING', $chart['pending'])
            ->addData('PROSES', $chart['proses'])
            ->addData('SUKSES', $chart['sukses'])
            ->addData('GAGAL', $chart['gagal'])
            ->addData('KADALUARSA', $chart['kadaluarsa'])
            ->setXAxis($chart['date'])
            ->setColors(['#6e81dc', '#fcc100', '#72d0fb', '#5fc27e', '#f44455', '#718093'])
            ->setFontFamily('DM Sans')
            ->setFontColor('#6e81dc');
    }
}

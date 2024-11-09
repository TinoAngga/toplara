<?php

namespace App\Charts\Admin;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class OrderChartPie
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($request): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');
        $type = $request->type;
        $chart['pending'] = \DB::table('orders')
                        ->where('status', 'pending')
                        ->select(\DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($type, function ($query) use ($type) {
                            return $query->where('order_type', $type);
                        })
                        ->first()->count;
        $chart['proses'] = \DB::table('orders')
                        ->where('status', 'proses')
                        ->select(\DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($type, function ($query) use ($type) {
                            return $query->where('order_type', $type);
                        })
                        ->first()->count;
        $chart['sukses'] = \DB::table('orders')
                        ->where('status', 'sukses')
                        ->select(\DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($type, function ($query) use ($type) {
                            return $query->where('order_type', $type);
                        })
                        ->first()->count;
        $chart['gagal'] = \DB::table('orders')
                        ->where('status', 'gagal')
                        ->select(\DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($type, function ($query) use ($type) {
                            return $query->where('order_type', $type);
                        })
                        ->first()->count;
        $chart['kadaluarsa'] = \DB::table('orders')
                        ->where('status', 'kadaluarsa')
                        ->select(\DB::raw('COUNT(id) AS count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->when($type, function ($query) use ($type) {
                            return $query->where('order_type', $type);
                        })
                        ->first()->count;
        return $this->chart->pieChart()
            ->setColors(['#fcc100', '#72d0fb', '#5fc27e', '#f44455', '#718093'])
            ->addData([$chart['pending'], $chart['proses'], $chart['sukses'], $chart['gagal'], $chart['kadaluarsa']])
            ->setLabels(['Pending', 'Proses', 'Sukses', 'Gagal', 'Kadaluarsa'])
            ->setFontFamily('DM Sans')
            ->setFontColor('#6e81dc');
    }
}

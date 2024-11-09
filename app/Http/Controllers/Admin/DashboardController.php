<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->theme != null && in_array($request->theme, ['light', 'dark'])) {
                setConfig('admin_main_template', $request->theme);
                session()->flash('alertClass', 'success');
                session()->flash('alertTitle', 'Berhasil !!.');
                session()->flash('alertMsg', 'Berhasil mengubah tema admin.');
                return redirect()->back();
            }

            // Ambil nilai filter dari request
            $endDate = $request->input('end_date');
            $endTime = $request->input('end_time');

            // Set nilai filter ke null jika nilainya kosong ('' dari form submission)
            if ($endDate === '') {
                $endDate = null;
            }
            if ($endTime === '') {
                $endTime = null;
            }

            // Inisialisasi variabel filter waktu akhir
            $endDateTime = null;

            // Konversi filter waktu ke dalam format yang dapat digunakan jika disediakan
            if ($endDate && $endTime) {
                $endDateTime = Carbon::parse("$endDate $endTime")->format('YdmHis');
            }

            // Count and sum for users
            $users = [
                'count' => DB::table('users')->count(),
                'sum' => DB::table('users')->sum('balance'),
            ];

            // Count and sum for orders
            $orders = [
                'count' => DB::table('orders')->count(),
                'sum' => DB::table('orders')->sum('price'),
            ];

            // Count and sum for deposits
            $deposits = [
                'count' => DB::table('deposits')->count(),
                'sum' => DB::table('deposits')->sum('amount'),
            ];

            // CONTROLLER HDI
            $paidServicesQuery = DB::table('orders')
                ->join('services', 'orders.service_id', '=', 'services.id')
                ->where('orders.provider_id', 1)
                ->where('orders.is_paid', 1)
                ->where('orders.status', '!=', 'SALAH ID')
                ->where(function ($query) {
                    $query->whereBetween('services.id', [517, 553])
                        ->orWhereBetween('services.id', [648, 653]);
                });

            // Terapkan filter tanggal dan waktu akhir jika disediakan
            if ($endDateTime) {
                $paidServicesQuery->where('orders.created_at', '<=', Carbon::createFromFormat('YdmHis', $endDateTime));
            }

            $paidServices = $paidServicesQuery->select('services.id', 'services.name')
                ->orderBy('services.id')
                ->get();

            $serviceCounts = [];
            foreach ($paidServices as $paidService) {
                $serviceId = $paidService->id;

                if (!isset($serviceCounts[$serviceId])) {
                    $serviceCounts[$serviceId] = [
                        'name' => $paidService->name,
                        'count' => 1,
                    ];
                } else {
                    $serviceCounts[$serviceId]['count'] += 1;
                }
            }

            $totalCount = 0;
            foreach ($serviceCounts as $service) {
                $totalCount += $service['count'];
            }
            
            // BARU HDI
            
$paidServicesQuery = DB::table('orders')
    ->join('services', 'orders.service_id', '=', 'services.id')
    ->where('orders.provider_id', 1)
    ->where('orders.is_paid', 1)
    ->where('orders.status', '!=', 'SALAH ID')
    ->where(function ($query) {
        $query->whereBetween('services.id', [517, 553])
              ->orWhereBetween('services.id', [648, 653]);
    });

// Terapkan filter tanggal dan waktu akhir jika disediakan
if ($endDateTime) {
    $paidServicesQuery->where('orders.created_at', '<=', Carbon::createFromFormat('YdmHis', $endDateTime));
}

$paidServices = $paidServicesQuery->select('services.id', 'services.name')
    ->orderByRaw("
        CASE
            WHEN services.name LIKE '%best seller%' THEN 950
            WHEN services.name REGEXP '^[0-9]+(\\.[0-9]+)?m$' THEN CAST(SUBSTRING_INDEX(services.name, 'm', 1) AS DECIMAL(10,2))
            WHEN services.name REGEXP '^[0-9]+(\\.[0-9]+)?b$' THEN CAST(SUBSTRING_INDEX(services.name, 'b', 1) AS DECIMAL(10,2)) * 1000
            ELSE 0
        END ASC
    ")
    ->get();

$serviceCountsss = [];

// Menggunakan array numerik untuk menjaga urutan
$orderedServices = [];

foreach ($paidServices as $paidService) {
    $serviceId = $paidService->id;
    $serviceName = strtolower($paidService->name); // Menggunakan lowercase untuk pencarian case-insensitive

    // Default count
    $count = 1;

    // Logika konversi: jika layanan mengandung angka dan satuan 'm' atau 'b'
    if (preg_match('/(\d+(\.\d+)?)(m|b)/i', $serviceName, $matches)) {
        $number = (float)$matches[1]; // Menggunakan float untuk menangani angka desimal
        $unit = strtolower($matches[3]); // Unit adalah 'm' atau 'b'

        if ($unit === 'm') {
            $count = $number * 1; // Jika ada 'm', kalikan dengan 1 (jutaan)
        } elseif ($unit === 'b') {
            $count = $number * 1000; // Jika ada 'b', kalikan dengan 1000 (milyaran)
        }
    }

    // Jika ingin menjaga urutan asli, tambahkan ke array numerik
    if (!isset($serviceCountsss[$serviceId])) {
        $serviceCountsss[$serviceId] = [
            'name' => $paidService->name,
            'count' => $count,
        ];
        $orderedServices[] = $serviceId; // Menyimpan urutan
    } else {
        // Update count untuk layanan yang sama
        $serviceCountsss[$serviceId]['count'] += $count;
    }
}

// Menghitung total nilai yang sudah dikonversi
$totalCountss = 0;
foreach ($serviceCountsss as $service) {
    $totalCountss += $service['count'];
}

            
            // END BARU HDI

            // END CONTROLLER HDI

            // RD CONTROLLER
            $paidServicessQuery = DB::table('orders')
                ->join('services', 'orders.service_id', '=', 'services.id')
                ->whereBetween('services.id', [554, 647])
                ->where('orders.provider_id', 1)
                ->where('orders.status', '!=', 'SALAH ID')
                ->where('orders.is_paid', 1);
                

            // Terapkan filter tanggal dan waktu akhir jika disediakan
            if ($endDateTime) {
                $paidServicessQuery->where('orders.created_at', '<=', Carbon::createFromFormat('YdmHis', $endDateTime));
            }

            $paidServicess = $paidServicessQuery->select('services.id', 'services.name')
                ->orderBy('services.id')
                ->get();

            $serviceCountss = [];
            foreach ($paidServicess as $paidService) {
                $serviceId = $paidService->id;

                if (!isset($serviceCountss[$serviceId])) {
                    $serviceCountss[$serviceId] = [
                        'name' => $paidService->name,
                        'count' => 1,
                    ];
                } else {
                    $serviceCountss[$serviceId]['count'] += 1;
                }
            }

            $totalCounts = 0;
            foreach ($serviceCountss as $service) {
                $totalCounts += $service['count'];
            }

            // END RD CONTROLLER
            
                        // BARU RD
            
$paidServicessQuery = DB::table('orders')
                ->join('services', 'orders.service_id', '=', 'services.id')
                ->whereBetween('services.id', [554, 647])
                ->where('orders.provider_id', 1)
                ->where('orders.status', '!=', 'SALAH ID')
                ->where('orders.is_paid', 1);

// Terapkan filter tanggal dan waktu akhir jika disediakan
if ($endDateTime) {
    $paidServicessQuery->where('orders.created_at', '<=', Carbon::createFromFormat('YdmHis', $endDateTime));
}

$paidServices = $paidServicesQuery->select('services.id', 'services.name')
    ->orderByRaw("
        CASE
            WHEN services.name LIKE '%best seller%' THEN 950
            WHEN services.name REGEXP '^[0-9]+(\\.[0-9]+)?m$' THEN CAST(SUBSTRING_INDEX(services.name, 'm', 1) AS DECIMAL(10,2))
            WHEN services.name REGEXP '^[0-9]+(\\.[0-9]+)?b$' THEN CAST(SUBSTRING_INDEX(services.name, 'b', 1) AS DECIMAL(10,2)) * 1000
            ELSE 0
        END ASC
    ")
    ->get();

$sserviceCount = [];
foreach ($paidServicess as $paidService) {
    $serviceId = $paidService->id;
    $serviceName = strtolower($paidService->name); // Menggunakan lowercase untuk pencarian case-insensitive

    // Default count
    $count = 1;

    // Logika konversi: jika layanan mengandung angka dan satuan 'm' atau 'b'
    if (preg_match('/(\d+(\.\d+)?)(m|b)/i', $serviceName, $matches)) {
    $number = (float)$matches[1]; // Menggunakan float untuk menangani angka desimal
    $unit = strtolower($matches[3]); // Unit adalah 'm' atau 'b'

    if ($unit === 'm') {
        $count = $number * 1; // Jika ada 'M', kalikan dengan 100
    } elseif ($unit === 'b') {
        $count = $number * 1000; // Jika ada 'B', kalikan dengan 1000
    }
}

    // Tambahkan atau update hitungan layanan dalam array serviceCountsss
    if (!isset($sserviceCount[$serviceId])) {
        $sserviceCount[$serviceId] = [
            'name' => $paidService->name,
            'count' => $count,
        ];
    } else {
        // Update count untuk layanan yang sama
        $sserviceCount[$serviceId]['count'] += $count;
    }
}

// Menghitung total nilai yang sudah dikonversi
$ttotalCount = 0;
foreach ($sserviceCount as $service) {
    $ttotalCount += $service['count'];
}
            
            // END BARU RD

            $page = 'Dashboard';

            // Widget array with users, orders, paidServices, and deposits data
            $widget = [
                'users' => $users,
                'orders' => $orders,
                'serviceCounts' => $serviceCounts,
                'serviceCountss' => $serviceCountss,
                'serviceCountsss' => $serviceCountsss,
                'sserviceCount' => $sserviceCount,
                'deposits' => $deposits,
                'totalCount' => $totalCount,
                'ttotalCount' => $ttotalCount,
                'totalCountss' => $totalCountss,
                'totalCounts' => $totalCounts, // Added total count
            ];

            // Pass data to the view
            return view('admin.dashboard', compact('page', 'widget', 'endDate', 'endTime'));
        } catch (\Exception $exception) {
            // Log the exception or handle it as needed
            return response()->view('errors.500', [], 500);
        }
    }
}

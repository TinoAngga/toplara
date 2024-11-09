<?php

namespace App\Http\Controllers\Primary;

use App\DataTables\Primary\OrderDataTable;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Libraries\CustomException;
use Illuminate\Http\Request;
use App\Services\Primary\Order\CheckoutService;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        if(!$request->ajax()) abort(405);
        try {
            $orderCheckout = (new CheckoutService())->handle($request);
            // dd($orderCheckout);
            if ($orderCheckout['status'] === true) {
                session()->flash('alertClass', 'success');
                session()->flash('alertTitle', 'Berhasil.');
                session()->flash('alertMsg', 'Pesanan berhasil dilakukan dengan nomor invoice <b>#' . $orderCheckout['invoice'] . '</b>');
                return response()->json([
                    'status' => true,
                    'type' => 'alert',
                    'msg' => 'Pesanan berhasil dilakukan dengan nomor invoice <b>#' . $orderCheckout['invoice'] . '</b>',
                    'redirect_url' => $orderCheckout['redirect_url']
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => $orderCheckout['msg'],
                ]);
            }


        } catch (CustomException $e) {
            Log::info($e->getCustomMessage());
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => 'Terjadi kesalahan !! harap hubungi admin',
            ], 200);
        }
    }
        public function search()
    {
        $page = [
            'title' => 'Cari Pesanan',
            'breadcrumb' => [
                'first' => 'Pesanan',
                'second' => 'Cari pesanan'
            ]
        ];
        return view('primary.order.search', compact('page'));
    }

    public function postSearch(Request $request)
    {
        if (!$request->ajax()) abort(405);
    
        // Ambil input dan lakukan sanitasi awal untuk menghapus karakter berbahaya
        $searchInput = htmlspecialchars($request->input('search'), ENT_QUOTES, 'UTF-8');
    
        // Validasi input dengan regex yang hanya memperbolehkan huruf, angka, tanda strip (-), dan pagar (#)
        $validator = makeValidator(['search' => $searchInput], [
            'search' => [
                'required',
                'regex:/^[A-Za-z0-9\-#]+$/'
            ],
        ], [
            'search.required' => 'Nomor invoice harus diisi.',
            'search.regex' => 'Nomor invoice hanya boleh berisi huruf, angka, tanda strip (-), dan tanda pagar (#).',
        ], ['search' => 'Nomor invoice']);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'msg' => $validator->errors()->toArray()
            ]);
        }
    
        // Cari pesanan berdasarkan nomor invoice yang telah disanitasi
        $order = Order::byInvoice($searchInput)->first();
        if ($order == null) {
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => 'Pesanan dengan invoice <b>#' . e($searchInput) . '</b> tidak ditemukan.'
            ]);
        }
    
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Pesanan dengan nomor invoice <b>#' . e($order->invoice) . '</b> berhasil ditemukan.',
            'redirect_url' => route('order.invoice', $order->invoice)
        ]);
    }


    public function invoice(String $invoice = null)
    {
        $order = Order::byInvoice($invoice)->with('service', 'payment', 'review')->first();

        if($order == null){
            session()->flash('alertClass', 'danger');
            session()->flash('alertTitle', 'Gagal.');
            session()->flash('alertMsg', 'Pesanan tidak ditemukan.');
            if (Auth::check() == true) return redirect()->route('order.history');
            return redirect(route('order.search.get'));
        }

        $page = [
            'title' => 'Invoice #' . $order->invoice,
            'breadcrumb' => [
                'first' => 'Pesanan',
                'second' => '#' . $order->invoice
            ]
        ];
        return view('primary.order.invoice', compact('order', 'page'));
    }
    public function history(OrderDataTable $dataTable)
    {
        if (request()->ajax() AND request('type') == 'select2_service' AND !empty(request('search'))) {
            $services = Service::with('category:id,name')
                ->select('id', 'name', 'service_category_id')
                ->where(function($query){
                    $query
                        ->where('name', 'LIKE', '%'.request('search').'%')
                        ->orWhereHas('category', function($query) {
                            $query
                                ->orWhere('name', 'like', "%".request('search')."%");
                    });
                })
                ->get();
            $data = [];
            foreach ($services as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'text' => $value->category->name . ' - ' . $value->name
                ];
            }
            return $data;
        }
        $page = [
            'title' => 'Riwayat Pesanan',
            'breadcrumb' => [
                'first' => 'Pesanan',
                'second' => 'Riwayat'
            ]
        ];
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $payments = PaymentMethod::paymentActive()->select('id', 'name')->get();
        $status = [
            'pending', 'proses', 'sukses', 'gagal', 'kadaluarsa'
        ];
        return $dataTable->render('primary.order.history', compact('page', 'paid', 'payments', 'status'));
    }

    public function popUpNotification()
    {
        if (request()->ajax() == false) abort(404);
        $order = Order::query()
            ->select('whatsapp_order', 'invoice', 'service_id', 'status', 'created_at')
            ->with('service:id,name,service_category_id', 'service.category:id,name,img')
            ->where('status', 'sukses')
            ->whereBetween('created_at', [now()->subDay(3)->format('Y-m-d H:i:s'), now()->format('Y-m-d H:i:s')])
            ->inRandomOrder()
            ->first();

        return response()->json([
            'status' => ($order == null ? false : true),
            'data' => ($order == null) ? null : [
                'img' => asset(config('constants.options.asset_img_service_category') . $order->service->category->img),
                'text' => hideString($order->whatsapp_order) . ' telah membeli ' . $order->service->category->name . ' - ' .  $order->service->name,
                'phone' => hideString($order->whatsapp_order),
            ]
        ]);
    }

    public function postReview(Request $request, $invoice = null)
    {
        if(!$request->ajax()) abort(405);
        $validator = makeValidator($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:500',
        ], [], ['order_id' => 'Pesanan', 'rating' => 'Rating', 'comment' => 'Komentar']);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);

        $order = Order::where('invoice', $invoice)->first();
        if ($order == null) {
            return response()->json([
                'status' => false,
                'type' => 'alert',
                'msg' => 'Pesanan tidak ditemukan.'
            ]);
        }
        $order->load('service');

        if($order->status != 'sukses') return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Pesanan tidak dapat diulas.'
        ]);

        $review = $order->review;
        if($review != null) return response()->json([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Pesanan sudah diulas.'
        ]);

        $order->review()->create([
            'service_category_id' => $order->service->service_category_id,
            'service_id' => $order->service_id,
            'invoice' => $order->invoice,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Pesanan berhasil diulas.',
            'redirect_url' => route('order.invoice', $order->invoice)
        ]);
    }
}

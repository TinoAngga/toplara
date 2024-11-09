<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentMethodRequest;
use Illuminate\Http\Request;
use App\DataTables\Admin\PaymentMethodDataTable;
use App\Services\Admin\PaymentMethod\StoreService;
use App\Services\Admin\PaymentMethod\UpdateService;
use App\Libraries\CustomException;

class PaymentMethodController extends Controller
{

    public function index(PaymentMethodDataTable $dataTable)
    {
        $page = 'Payment Method';
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $type = config('constants.options.payment_method_type');
        $payment_gateway = config('constants.options.payment_method_type');
        $is_public = ['1' => 'Ya', '0' => 'Tidak'];
        return $dataTable->render('admin.payment-method.index', compact('page', 'status', 'type', 'is_public', 'payment_gateway'));
    }

    public function create()
    {
        $type = config('constants.options.payment_method_type');
        $payment_gateway = config('constants.options.payment_gateway');
        return view('admin.payment-method.create', compact('type', 'payment_gateway'));
    }

    public function store(PaymentMethodRequest $request, StoreService $storeService)
    {
        try {
            $store = $storeService->handle($request);
            return response()->json([
                'status'  => true,
                'type' => 'alert',
                'msg' => 'Metode Pembayaran berhasil ditambahkan #' . $store->id . '.'
            ]);
        } catch (CustomException $e) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Terjadi kesalahan.'
            ]);
        }
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-method.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->id === 1) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Metode pembayaran utama tidak bisa di hapus.'
            ], 405);
        }
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $type = config('constants.options.payment_method_type');
        $payment_gateway = config('constants.options.payment_gateway');
        $is_public = ['1' => 'Ya', '0' => 'Tidak'];
        return view('admin.payment-method.edit', compact('paymentMethod', 'status', 'type', 'payment_gateway', 'is_public'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod, UpdateService $updateService)
    {
        try {
            $updateService->handle($request, $paymentMethod);
            return response()->json([
                'status'  => true,
                'type' => 'alert',
                'msg' => 'Metode Pembayaran berhasil diubah #' . $paymentMethod->id . '.'
            ]);
        } catch (CustomException $e) {
            return response()->json($e->getCustomMessage());
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->id === 1) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Metode pembayaran utama tidak bisa di hapus.'
            ]);
        }
        $paymentMethod->delete();
        if (file_exists(config('constants.options.asset_img_payment_method') . $paymentMethod->img)) {
            unlink(config('constants.options.asset_img_payment_method') . $paymentMethod->img);
        }
        if (file_exists(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode)) {
            unlink(config('constants.options.asset_img_qr_code') . $paymentMethod->qrcode);
        }
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil menghapus data #'.$paymentMethod->id.'.'
        ]);
    }

    public function switchStatus(Request $request, PaymentMethod $paymentMethod){
        if ($request->ajax() == false) abort('404');
        if (!in_array($request->type, ['status', 'manual', 'public']) AND !in_array($request->value, ['0', '1'])) {
            return response()->json([
                'status'  => false,
                'type' => 'alert',
                'msg' => 'Aksi tidak diperbolehkan'
            ]);
        }
        if ($request->type == 'status') $paymentMethod->is_active = $request->value;
        if ($request->type == 'manual') $paymentMethod->is_manual = $request->value;
        if ($request->type == 'public') $paymentMethod->is_public = $request->value;
        $paymentMethod->save();
        return response()->json([
            'status'  => true,
            'type' => 'alert',
            'msg' => 'Berhasil mengubah data #'.$paymentMethod->id.'.'
        ]);
    }
}

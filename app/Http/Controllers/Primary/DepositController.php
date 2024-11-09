<?php

namespace App\Http\Controllers\Primary;

use App\DataTables\Primary\DepositDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Primary\DepositRequest;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Services\Primary\Deposit\RequestService;
use App\Libraries\CustomException;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    public function index()
    {
        $page = [
            'title' => 'Deposit',
            'breadcrumb' => [
                'first' => 'Deposit'
            ]
        ];
        $amount = [10000, 20000, 50000, 100000, 200000, 500000, 1000000];
        $payments = PaymentMethod::paymentPublicActive()
                ->whereNotIn('type', ['saldo'])
                ->get();
        return view('primary.deposit.index', compact('page', 'amount', 'payments'));
    }
    public function request(DepositRequest $request, RequestService $requestService)
    {
        if(!$request->ajax()) abort(404);
        try {
            $deposit = $requestService->handle($request);
            if ($deposit['status'] === true) {
                return response()->json([
                    'status' => true,
                    'type' => 'alert',
                    'msg' => 'Pesanan berhasil dilakukan dengan nomor invoice <b>#' . $deposit['invoice'] . '</b>',
                    'redirect_url' => $deposit['redirect_url']
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => $deposit['msg'],
                ]);
            }


        } catch (CustomException $e) {
            Log::info($e);
            return response()->json($e->getCustomMessage());
        }
    }
    public function history(DepositDataTable $dataTable)
    {
        config(['app.debug' => true]);
        $page = [
            'title' => 'Riwayat Deposit',
            'breadcrumb' => [
                'first' => 'Deposit',
                'second' => 'Riwayat Deposit'
            ]
        ];
        $paid = ['1' => 'Lunas', '0' => 'Belum Lunas'];
        $payments = PaymentMethod::paymentActive()
            ->select('id', 'name')
            ->get();
        $status = [
            'pending', 'sukses', 'gagal', 'kadaluarsa'
        ];
        return $dataTable->render('primary.deposit.history', compact('page', 'paid', 'payments', 'status'));
    }

    public function invoice(String $invoice = null)
    {
        $deposit = Deposit::byInvoice($invoice)->with('payment')->first();
        if($deposit == null){
            session()->flash('alertClass', 'danger');
            session()->flash('alertTitle', 'Gagal.');
            session()->flash('alertMsg', 'Deposit tidak ditemukan.');
            return redirect(route('deposit.history'));
        }
        $page = [
            'title' => 'Invoice #' . $deposit->invoice,
            'breadcrumb' => [
                'first' => 'Pesanan',
                'second' => '#' . $deposit->invoice
            ]
        ];
        return view('primary.deposit.invoice', compact('deposit', 'page'));
    }
    public function getPrice(Request $request)
    {
        if(!$request->ajax()) abort(405);

        $payments = PaymentMethod::paymentActive()->get();
        $payment_methods = [];
        foreach ($payments as $key => $value) {
            $payment_offline = false;
            $payment_description = null;
            if(!is_null($value->time_used) && !is_null($value->time_stopped)){
                if (onlineHours($value->time_used, $value->time_stopped) == false) {
                    $payment_offline = true;
                    $payment_description = 'Saat ini metode pembayaran ini sedang offline';
                }
                // if (strtotime(date('H:i')) <= strtotime($value->time_used) && strtotime(date('H:i')) >= strtotime($value->time_stopped)) {
                //     echo 'yes';
                //     $payment_offline = true;
                //     $payment_description = 'Saat ini metode pembayaran ini sedang offline';
                // }
            }
            $totalPrice = $request->amount + $value->fee + ($request->amount * convertPercent($value->fee_percent));
            if ($value->min_amount > $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Minimal pembayaran Rp ' . currency($value->min_amount);
            } else if ($value->max_amount < $totalPrice) {
                $payment_offline = true;
                $payment_description = 'Maksimal pembayaran Rp ' . currency($value->max_amount);
            }
            $payment_methods[] = [
                'id' => $value->id,
                'type' => $value->type,
				'price' => [
                    'integer' => ($payment_offline === false) ? ceil($totalPrice) : 0,
                    'string' => ($payment_offline === false) ? 'Rp ' . currency(ceil($totalPrice)) : '<i>Offline</i>'
                ],
                'offline' => $payment_offline,
                'description' => $payment_description,
                'valid_amount' => [
                    'min' => $value->min_amount,
                    'max' => $value->max_amount
                ]
			];
        }
        return response()->json([
            'status' => true,
            'data' => $payment_methods
        ]);
    }
}


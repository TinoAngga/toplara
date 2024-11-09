<?php

namespace App\Services\Primary\Deposit;

use App\Jobs\DepositNotifyJob;
use App\Libraries\CustomException;
use App\Libraries\Tripay;
use App\Libraries\Paydisini;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestService {

    public function handle(Object $data){
        if(!$data->ajax()) exit(abort(404));
        $input = [
            'user_id' => Auth::user()->id,
            'payment_id' => $data->payment,
            'invoice' => !is_null(getConfig('deposit_invoice_code')) ? getConfig('deposit_invoice_code') . time() . rand(0000, 9999) : time() . rand(0000, 9999),
            'amount' => $data->amount,
            'balance' => $data->amount,
            'unique_code' => 0,
            'fee' => 0,
            'is_paid' => 0,
            'status' => 'pending',
            'additional_data' => $data->additional_data ?? null,
            'ip_address' => $data->ip(),
            'payment_gateway' => null,
            'payment_gateway_request_response' => null,
            'payment_gateway_callback_response' => null
        ];

        DB::beginTransaction();
        try {
            $payment = PaymentMethod::find($input['payment_id']);

            if ($payment == null || $payment->is_active == 0) return setResponse(false, 'Metode pembayaran tidak tersedia.');

            if (onlineHours($payment->time_used, $payment->time_stopped) == false) return setResponse(false, 'Metode pembayaran ini sedang offline.');

            // START GET FEE //
            $input['fee'] = $payment->fee + ($input['amount'] * convertPercent($payment->fee_percent));
            // START GET PRICE //
            $input['amount'] = $input['amount'] + $input['fee'];
            // END GET PRICE //
            if ($payment->type == 'bank_transfer') {
                for ($i = 0; $i < 100; $i++) {
                    $unique_random = rand(000,999);
                    $isExists = Deposit::select('id')
                        ->where(function ($query) use ($input, $unique_random) {
                        $query
                            ->where('amount', $input['amount'] + $unique_random)
                            ->where('is_paid', 0);
                    })->exists();
                    if ($isExists) continue;
                    break;
                }
                $input['unique_code'] = $unique_random;
                // $input['fee'] = $input['fee'] + $unique_random;
                $input['amount'] = $input['amount'] + $unique_random;
            }

            if ($input['amount'] < $payment->min_amount) return setResponse(false, 'Minimal deposit ' . $payment->name . ' Rp. ' . number_format($payment->min_amount, 0, ',', '.'));
            if ($input['amount'] > $payment->max_amount) return setResponse(false, 'Maksimal deposit ' . $payment->name . ' Rp. ' . number_format($payment->max_amount, 0, ',', '.'));

            // START CHECK USER AND CREATE DEPOSIT //
            $result = [];
            if ($payment->is_manual == 0 AND $payment->payment_gateway == 'tripay') {
                $tripay = new Tripay(getConfig('tripay_api_key') ?? config('constants.options.tripay_api_key'));
                $paramRequest = [
                    'tripay_merchant_code' => getConfig('tripay_merchant_code') ?? config('constants.options.tripay_merchant_code'),
                    'tripay_private_key' => getConfig('tripay_private_key') ?? config('constants.options.tripay_private_key'),
                    'method' => $payment->payment_gateway_code,
                    'merchant_ref' => 'DEPOSIT-' . $input['invoice'],
                    'amount' => $input['amount'],
                    'customer' => [
                        'name' => Auth::user()->full_name,
                        'email' => Auth::user()->email,
                        'phone' => null
                    ],
                    'order_items' => [
                        'sku' => 'DEPOSIT-' . $input['invoice'],
                        'name' => $payment->name
                    ],
                    'return_url' => url('deposit/invoice/' . $input['invoice'])
                ];
                $request = $tripay->closedPayment($paramRequest);
                if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran.');
                $input['payment_gateway_request_response'] = $request['curl'];
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => $request['data']['checkout_url']];
            } else if ($payment->is_manual == 0 AND $payment->payment_gateway == 'paydisini') {
                $paydisini = new Paydisini(getConfig('paydisini_api_key'));
                $paramRequest = [
                    'key'               => getConfig('paydisini_api_key') ?? '',
                    'request'           => 'new',
                    'unique_code'       => 'DEPOSIT-' . $input['invoice'],
                    'service_code'      => $payment->payment_gateway_code,
                    'amount'            => $input['amount'],
                    'note'              => '-',
                    'valid_time'        => 7200,
                    'ewallet_phone'     => null,
                    'phone'             => ($data->phone_ewallet ?? null),
                    'type_fee'          => 2
                ];
                $request = $paydisini->create($paramRequest);
                if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                $input['payment_gateway_request_response'] = $request['curl'];
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('deposit/invoice/' . $input['invoice'])];
            } else {
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('deposit/invoice/' . $input['invoice'])];
            }
            $deposit = Deposit::create($input);
            // END CHECK USER AND CREATE DEPOSIT//
            dispatch(new DepositNotifyJob($deposit
                ->with('payment', 'user')
                ->find($deposit->id)))
                ->delay(now()->addSeconds(5));
            DB::commit();
            return $result;
        } catch (\Throwable $e) {
            Log::info($e);
            DB::rollBack();
            throw new CustomException([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }



    }
}

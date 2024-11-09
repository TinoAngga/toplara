<?php

namespace App\Services\Primary\Account;

use App\Libraries\CustomException;
use App\Models\PaymentMethod;
use App\Models\UserLevel;
use App\Models\UserUpgrade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libraries\Paydisini;
use App\Libraries\Tripay;

class UpgradeLevelService {

    public function handle(Object $data){
        $input = [
            'user_id' => Auth::user()->id,
            'payment_id' => $data->payment,
            'invoice' => !is_null(getConfig('upgrade_level_invoice_code')) ? getConfig('upgrade_level_invoice_code') . time() . rand(000, 999) : time() . rand(000, 999),
            'level' => $data->level,
            'price' => 0,
            'unique_code' => 0,
            'fee' => 0,
            'status' => 'pending',
            'is_paid' => 0,
            'payment_gateway_request_response' => null,
            'payment_gateway_callback_response' => null
        ];

        // START CHECK PAYMENT //
        $payment = PaymentMethod::query()
            ->whereIn('id', [$input['payment_id']])
            ->first();

        if (!$payment) return setResponse(false, 'Pembayaran tidak tersedia.');

        // CHECK ONLINE OR OFFLINE PAYMENT //
        if(!is_null($payment->time_used) && !is_null($payment->time_stopped)){
            if (onlineHours($payment->time_used, $payment->time_stopped) == false) return setResponse(false, 'Metode pembayaran ini sedang offline.');
        }

        $level = UserLevel::find($data->level);

        if (!$level) return setResponse(false, 'Level tidak tersedia.');

        $result = [];
        DB::beginTransaction();

        if (Auth::user()->level == $level->name) return setResponse(false, 'Tidak boleh upgrade level yang sama dengan level sekarang');
        try {
            $input['level'] = $level->name;
            // START GET FEE SERVICE //
            $input['fee'] = $payment->fee + ($level->price * convertPercent($payment->fee_percent));
            // END GET FEE SERVICE //

            // START GET PRICE AND PROFIT //
            $input['price'] = ceil($level->price + $input['fee']);
            $input['profit'] = ceil($level->profit + $input['fee']);
            // END GET PRICE AND PROFIT //

            if ($payment->type == 'bank_transfer') {
                for ($i = 0; $i < 100; $i++) {
                    $unique_random = rand(111,999);
                    $isExists = UserUpgrade::where(function ($query) use ($input, $unique_random) {
                        $query->where('price', $input['price'] + $unique_random)
                        ->where('is_paid', 0);
                    })->exists();
                    if ($isExists) continue;
                    break;
                }
                $input['unique_code'] = $unique_random;
                // $input['fee'] = $input['fee'] + $unique_random;
                $input['price'] = $input['price'] + $unique_random;
                $input['profit'] = $input['profit'] + $unique_random;
            }
            if ($input['price'] < $payment->min_amount) return setResponse(false, 'Minimal order ' . $payment->name . ' Rp. ' . number_format($payment->min_amount, 0, ',', '.'));
            if ($input['price'] > $payment->max_amount) return setResponse(false, 'Maksimal order ' . $payment->name . ' Rp. ' . number_format($payment->max_amount, 0, ',', '.'));
            // START CHECK USER AND CREATE ORDER UPGRADE LEVEL//
            if (Auth::check() AND $payment->type == 'saldo') {
                // CHECK USER AND PAYMENT SALDO
                $user = User::find(Auth::user()->id);
                if ($user->balance < $input['price']) return setResponse(false, 'Sisa saldo Anda tidak cukup untuk melakukan upgrade level.');
                $user->balance = $user->balance - $input['price'];
                $user->level = $level->name;
                $user->save();

                // INSERT TO MUTATION BALANCE
                Auth::user()->mutation()->create([
                    'type' => 'credit',
                    'category' => 'upgrade-level',
                    'description' =>'Upgrade Level ke <b>' . ucwords($level->name) .'</b> #'. $input['invoice'],
                    'amount' => $input['price'],
                    'beginning_balance' => $user->balance + $input['price'],
                    'last_balance' => $user->balance,
                ]);
                // SET PAID AND SUCCESS
                $input['is_paid'] = 1;
                $input['status'] = 'sukses';

                // SET BONUS
                if ($level->get_balance > 0) {
                    $user->balance = $user->balance + $level->get_balance;
                    $user->save();
                    Auth::user()->mutation()->create([
                        'type' => 'debit',
                        'category' => 'upgrade-level',
                        'description' => 'Bonus upgrade level '.$input['level'].'.',
                        'amount' => $level->get_balance,
                        'beginning_balance' => $user->balance - $level->get_balance,
                        'last_balance' => $user->balance,
                    ]);
                }

                $input['is_paid'] = 1;
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('account/upgrade/invoice/' . $input['invoice'])];
            } else {
                if ($payment->is_manual == 0 AND $payment->payment_gateway == 'tripay') {
                    $tripay = new Tripay(getConfig('tripay_api_key') ?? config('constants.options.tripay_api_key'));
                    $paramRequest = [
                        'tripay_merchant_code' => getConfig('tripay_merchant_code') ?? config('constants.options.tripay_merchant_code'),
                        'tripay_private_key' => getConfig('tripay_private_key') ?? config('constants.options.tripay_private_key'),
                        'method' => $payment->payment_gateway_code,
                        'merchant_ref' => 'UPLEVEL-' . $input['invoice'],
                        'amount' => $input['price'],
                        'customer' => [
                            'name' => Auth::user()->full_name ?? 'GUEST',
                            'email' => Auth::user()->email ?? 'guest@mail.com',
                        ],
                        'order_items' => [
                            'sku' => 'UPLEVEL-' . $input['invoice'],
                            'name' => $payment->name
                        ],
                        'return_url' => url('order/invoice/' . $input['invoice'])
                    ];
                    $request = $tripay->closedPayment($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['curl'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => $request['data']['checkout_url']];
                }
                if ($payment->is_manual == 0 AND $payment->payment_gateway == 'paydisini') {
                    $paydisini = new Paydisini(getConfig('paydisini_api_key'));
                    $paramRequest = [
                        'key'               => getConfig('paydisini_api_key') ?? '',
                        'request'           => 'new',
                        'unique_code'       => 'UPLEVEL-' . $input['invoice'],
                        'service_code'      => $payment->payment_gateway_code,
                        'amount' => $input['price'],
                        'note'              => '-',
                        'valid_time'        => 7200,
                        'ewallet_phone'     => null,
                        'phone'             => ($data->phone_ewallet ?? null),
                        'type_fee'          => 2
                    ];
                    $request = $paydisini->create($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['curl'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], url('account/upgrade/invoice/' . $input['invoice'])];
                }
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('account/upgrade/invoice/' . $input['invoice'])];
            }
            // END CHECK USER AND CREATE ORDER UPGRADE LEVEL //
            $userUpgrade = UserUpgrade::create($input);
            DB::commit();
            return $result;
        } catch (\Throwable $e) {
            Log::info($e);
            DB::rollBack();
            throw new CustomException([
                'status' => false,
                'type' => 'alert',
                'msg' => $e->getMessage()
            ]);
        }
    }
}

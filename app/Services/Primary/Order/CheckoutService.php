<?php

namespace App\Services\Primary\Order;

use App\Events\OrderPlaced;
use App\Jobs\OrderAlertNotifyJob;
use App\Jobs\OrderNotifyJob;
use App\Libraries\CustomException;
use App\Libraries\Tripay;
use App\Libraries\Paydisini;
use App\Libraries\Xendit;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Mail\OrderAlertNotifyMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DateTime;
use DateTimeZone;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

class CheckoutService {
    public function handle($data, $isAPI = false){
        $user = Auth::check() == true ? Auth::user() : null;
        $input = [
            'service_type' => null,
            'user_id' => isset($user->id) ? $user->id : 1,
            'service_id' => $data->service ?? null,
            'payment_id' => $data->payment ?? 1,
            'provider_id' => null,
            'invoice' => !is_null(getConfig('order_invoice_code')) ? getConfig('order_invoice_code') . time() . rand(000, 999) : time() . rand(000, 999),
            'data' => $data->data,
            'additional_data' => $data->additional_data ?? null,
            'additional_info' => session('nickname_game') ?? null,
            'price' => 0,
            'profit' => 0,
            'unique_code' => 0,
            'fee' => 0,
            'is_paid' => 0,
            'is_refund' => 0,
            'status' => 'pending',
            'ip_address' => $data->ip(),
            'order_type' => isset($user->id) ? 'member' : 'public',
            'payment_gateway_request_response' => null,
            'payment_gateway_callback_response' => null,
            'email_order' => $data->email ?? null,
            'whatsapp_order' => $data->whatsapp ?? null
        ];

        if ($isAPI) {
            $user = $data->get('user');
            $input['user_id'] = $user->id;
            $input['invoice'] = !is_null(getConfig('order_invoice_code')) ? getConfig('order_invoice_code') . strtoupper(uniqid()) : strtoupper(uniqid());
            $input['data'] = $data->target;
            $input['additional_data'] = $data->additional_target ?? null;
            $input['order_type'] = 'member';
            $input['is_api'] = 1;
            $input['email_order'] = $user->email ?? null;
            $input['whatsapp_order'] = $user->phone_number ?? null;
        }

        DB::beginTransaction();
        $result = [];
        try {
            // START CHECK SERVICE //
            $service = Service::query()
                ->whereIn('id', [$input['service_id']])
                ->first();
            if (!$service) return setResponse(false, 'Layanan tidak tersedia.');

            if ($service->is_active == 0) return setResponse(false, 'Layanan sedang mengalami gangguan pada server.');
            // END START CHECK SERVICE //

            $input['service_type'] = $service->service_type;
            if (in_array($input['service_type'], ['pulsa-reguler', 'pulsa-transfer', 'paket-internet', 'paket-telepon'])) {
                if (substr($input['data'], 0, 3) == '628') $input['data'] = str_replace('628', '08', $input['data']);
            }

            // START CHECK SERVICE CATEGORY //
            $serviceCategory = ServiceCategory::query()
                ->select('id', 'name', 'counter', 'slug')
                ->whereIn('id', [$service->service_category_id])
                ->first();
            if (!$serviceCategory) return setResponse(false, 'Layanan tidak tersedia');
            // END START CHECK SERVICE CATEGORY //

            // START CHECK PROVIDER //
            $provider = Provider::active()
                ->select('id')
                ->whereIn('id', [$service->provider_id])
                ->first();
            if (!$provider) return setResponse(false, 'Layanan sedang mengalami gangguan pada server.');
            $input['provider_id'] = $provider->id;
            // END START CHECK PROVIDER //

            // START CHECK PAYMENT //
            $payment = PaymentMethod::query()
                ->whereIn('id', [$input['payment_id']])
                ->first();

            if (!$payment) return setResponse(false, 'Pembayaran tidak tersedia.');

            // CHECK ONLINE OR OFFLINE PAYMENT //
            if(!is_null($payment->time_used) && !is_null($payment->time_stopped)){
                if (onlineHours($payment->time_used, $payment->time_stopped) == false) return setResponse(false, 'Metode pembayaran ini sedang offline.');
            }

            // END START CHECK PAYMENT //

            // START GET LEVEL USER ORDER //
            $getUserLevel = isset($user->id) ? $user->level : 'public';
            // END GET LEVEL USER ORDER //

            $input['price'] = $service->price->{$getUserLevel};
            $input['profit'] = $service->profit->{$getUserLevel};

            // GET TOTAL PRICE AND PROFIT IN JOKI MLBB RANK STAR//
            if ($serviceCategory->slug == 'joki-mobile-legends-ranked') $input['price'] = $input['price'] * $data->star;
            if ($serviceCategory->slug == 'joki-mobile-legends-ranked') $input['profit'] = $input['profit'] * $data->star;

            // START GET FEE SERVICE //
            $input['fee'] = $payment->fee + ($input['price'] * convertPercent($payment->fee_percent));
            // END GET FEE SERVICE //

            // START GET PRICE AND PROFIT //
            $input['price'] = ceil($input['price']  + $input['fee']);
            $input['profit'] = ceil($input['profit'] + $input['fee']);
            // END GET PRICE AND PROFIT //

            if ($payment->type == 'bank_transfer') {
                for ($i = 0; $i < 100; $i++) {
                    $unique_random = rand(111,999);

                    $isExists = Order::select('id')
                        ->where(function ($query) use ($input, $unique_random) {
                        $query
                            ->where('price', $input['price'] + $unique_random)
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

            // START CHECK USER AND CREATE ORDER//
            if ($user AND $payment->type == 'saldo') {

                if(date_diffs(lastTransaction($user->id), now(), 'second') < 15) return setResponse(false, 'Transaksi terbatas, coba lagi dalam 15 Detik.');

                // CHECK USER AND PAYMENT SALDO
                $user = User::whereIn('id', [$user->id])->select('id', 'balance')->first();

                if (($user->balance - $input['price']) < 0) return setResponse(false, 'Sisa saldo Anda tidak cukup untuk membuat pesanan ini.');
                if ($user->balance < $input['price']) return setResponse(false, 'Sisa saldo Anda tidak cukup untuk membuat pesanan ini.');
                $user->balance = $user->balance - $input['price'];
                $user->save();

                // INSERT TO MUTATION BALANCE
                $user->mutation()->create([
                    'type' => 'credit',
                    'category' => 'order',
                    'description' => 'Order  ' . $serviceCategory->service_type . ' - ' . $service->name .' ' . $serviceCategory->name . ' #'. $input['invoice'],
                    'amount' => $input['price'],
                    'beginning_balance' => $user->balance + $input['price'],
                    'last_balance' => $user->balance,
                ]);

                $input['is_paid'] = 1;
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
            } else {
                if ($payment->is_manual == 0 AND $payment->payment_gateway == 'tripay') {
                    $tripay = new Tripay(getConfig('tripay_api_key') ?? config('constants.options.tripay_api_key'));
                    $paramRequest = [
                        'tripay_merchant_code' => getConfig('tripay_merchant_code') ?? config('constants.options.tripay_merchant_code'),
                        'tripay_private_key' => getConfig('tripay_private_key') ?? config('constants.options.tripay_private_key'),
                        'method' => $payment->payment_gateway_code,
                        'merchant_ref' => 'ORDER-' . $input['invoice'],
                        'amount' => $input['price'],
                        'customer' => [
                            'name' => $user->full_name ?? 'GUEST',
                            'email' => $user->email ?? 'guest@mail.com',
                        ],
                        'order_items' => [
                            'sku' => 'ORDER-' . $input['invoice'],
                            'name' => $payment->name
                        ],
                        'return_url' => url('order/invoice/' . $input['invoice'])
                    ];
                    $request = $tripay->closedPayment($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['curl'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
                }
                if ($payment->is_manual == 0 AND $payment->payment_gateway == 'paydisini') {
                    $paydisini = new Paydisini(getConfig('paydisini_api_key'));
                    $paramRequest = [
                        'key'               => getConfig('paydisini_api_key') ?? '',
                        'request'           => 'new',
                        'unique_code'       => 'ORDER-' . $input['invoice'],
                        'service_code'      => $payment->payment_gateway_code,
                        'amount'            => $input['price'],
                        'note'              => '-',
                        'valid_time'        => 7200,
                        'phone'             => ($data->phone_ewallet ?? null),
                        'type_fee'          => 2
                    ];
                    $request = $paydisini->create($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['curl'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
                }
                if (strtolower($payment->payment_gateway_code) == 'qris' AND $payment->payment_gateway == 'xendit') {
                    $xendit = new Xendit(getConfig('xendit_api_key') ?? config('constants.options.xendit_api_key'));
                    $paramRequest = [
                        'external_id' => 'ORDER-' . $input['invoice'],
                        'type' => 'DYNAMIC',
                        'callback_url' => url('callback/xendit'),
                        'amount' => $input['price']
                    ];
                    $request = $xendit->createQRCodes($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['json'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
                }
                if ($payment->payment_gateway == 'xendit' AND $payment->type == 'virtual_account') {
                    $xendit = new Xendit(getConfig('xendit_api_key') ?? config('constants.options.xendit_api_key'));
                    $expired_date = new DateTime();
					$expired_date->modify('+1 day');
					$expired_date->setTimezone(new DateTimeZone('UTC'));

					$paramRequest = [
						'external_id' => 'ORDER-' . $input['invoice'],
						'bank_code' => $payment->payment_gateway_code,
						'name' => $user->full_name ?? 'GUEST',
						'is_closed' => true,
						'expected_amount' => $input['price'],
						'expiration_date' => $expired_date->format(DateTime::ISO8601),
						'is_single_use' => true
					];
                    $request = $xendit->createVA($paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['json'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
                }
                if ($payment->is_manual == 0 AND $payment->payment_gateway == 'linkqu') {
                    $linkqu = new LinkQU(false);
                    $paramRequest = [
                        'amount' => $input['price'],
                        'partner_reff' => 'ORDER-' . $input['invoice'],
                        'customer_id' => $user->id ?? time(),
                        'customer_name' => $user->full_name ?? 'GUEST',
                        'customer_phone' => $user->phone ?? '62813' . rand(00000000, 99999999),
                        'customer_email' => $user->email ?? 'guest@guest.com',
                    ];
                    $request = $linkqu->createInvoice($payment->payment_gateway_code, $paramRequest);
                    if ($request == false) return setResponse(false, 'Terjadi kesalahan pada sistem pembayaran');
                    $input['payment_gateway_request_response'] = $request['json'];
                    $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
                }
                $result = ['status' => true, 'invoice' => $input['invoice'], 'redirect_url' => url('order/invoice/' . $input['invoice'])];
            }
            // END CHECK USER AND CREATE ORDER//
            if (preg_match("/joki-mobile-legend/i", $serviceCategory->slug)) {
                $input['additional_info'] = 'Hero=' . ($data->hero ?? 'No select hero') . '|Login=' . $data->login . '|Catatan=' . ($data->note ?? 'tanpa catatan') . '|User ID & Nickname=' . $data->user_nickname;
                $input['whatsapp_order'] = $data->whatsapp ?? null;
                if ($serviceCategory->slug == 'joki-mobile-legends-ranked') $input['additional_info'] .= '|Star=' . $data->star;
            }

            $order = Order::create($input);
            
            if (!is_null($input['email_order'])) {
            Mail::to($input['email_order'])->send(new OrderAlertNotifyMail($order));
}

            $serviceCategory->counter = $serviceCategory->counter + 1;
            $serviceCategory->save();

            // NOTIFY AND EVENT ORDER
            if (Auth::check() AND $payment->type == 'saldo' AND $provider->is_manual == 0) OrderPlaced::dispatch($order);
            // if (Auth::check() !== true || $payment->type !== 'saldo') {
            //     OrderAlertNotifyJob::dispatch($order)->delay(now()->addSeconds(3));
            // }
            OrderNotifyJob::dispatch($order)->delay(now()->addSeconds(3));
            DB::commit();
            if ($isAPI == true) return [
                'id' => $order->invoice,
                'category' => $serviceCategory->name,
                'service' => $service->name,
                'target' => $order->data,
                'additional_target' => $order->additional_data,
                'price' => $order->price,
                'note' => $order->provider_order_description ?? '',
                'last_balance' => $user->balance,
                'status' => $order->status,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i:s'),
            ];
            return $result;
        } catch (Exception $e) {
            Log::info('ERROR CHECKOUT SERVICE => ' . $e);
            DB::rollBack();
            throw new CustomException([
                'status' => false,
                'type' => 'alert',
                'msg' => $e->getMessage()
            ]);

        }
    }
}

<?php

namespace App\Services\Primary\Order;

use App\Jobs\OrderNotifyJob;
use App\Libraries\AgenHDI;
use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;

class SendToProviderService {
    public function handle($order){
        \Log::info("dari cronjob");

        $provider = Provider::where('id', $order->provider_id)
            ->where('is_manual', 0)
            ->first();
        $service = Service::find($order->service_id);

        $headers = null; // SET DEFAULT HEADER

        // SET PARAMS
        if ($provider->id == 2) { // VIP PAYMENT
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'order',
                'service' => $service->provider_service_code,
                'data_no' => $order->data,
                'data_zone' => $order->additional_data ?? null,
            ];
        }
        // REQUEST ORDER TO PROVIDER
        try {
            $request = Curl::request('POST', $provider->api_url_order, $params, $headers);
            if ($request) {
                $response = json_decode($request, true);
                // SET RESPONSE
                $response_order_placed = false;
                $response_order_placed_message = null;
                // GET RESPONSE
                if ($provider->id == 2) { // VIP
                    $response_order_placed = (isset($response['data']['trxid']) AND $response['data']['trxid']) ? $response['data']['trxid'] : false;
                    $response_order_placed_message = (isset($response['data']['message']) AND $response['data']['message']) ? $response['data']['message'] : null;
                }

                // CHECK RESPONSE
                if ($response_order_placed == false) {
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'ORDER | ' . $response_order_placed_message,
                        'order_response' => $request,
                    ]);
                    Order::find($order->id)->update([
                        'status' => 'gagal',
                    ]);
                    return [
                        'status' => false,
                        'type' => 'alert',
                        'msg' => 'Gagal !! Harap cek api provider log.',
                    ];
                } else {
                    // UPDATE ORDER
                    Order::find($order->id)->update([
                        'status' => $provider->id == 5 ? 'sukses' : 'proses',
                        'provider_order_id' => $response_order_placed,
                    ]);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'ORDER | ' . $response_order_placed,
                        'order_response' => $request,
                    ]);
                    return [
                        'status' => 'success',
                        'type' => 'alert',
                        'msg' => 'Berhasil !! Pesanan berhasil di kirim ke provider_api.',
                    ];
                }
                // ORDER NOTIF
                $order = Order::query()
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'username', 'level', 'email');
                    },
                    'payment' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'service' => function ($query) {
                        $query->select('id', 'service_category_id', 'name');
                    },
                ])
                ->find($order->id);
                \Mail::send(new \App\Mail\OrderNotifyMail($this->order));
                // dispatch(new OrderNotifyJob($order))->delay(now()->addSeconds(2));
            } else {
                ProviderApiLog::create([
                    'provider_id' => $provider->id,
                    'order_id' => $order->id,
                    'description' => 'Gagal ketika melakukan permintaan order ke provider',
                    'order_response' => $request,
                ]);
                \Log::error('Gagal ketika melakukan permintaan order ke provider ' . $provider->name . ' error curl request');
                return [
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Gagal !! Harap cek api provider log.',
                ];
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            ProviderApiLog::create([
                'provider_id' => $provider->id,
                'order_id' => $order->id,
                'description' => $e->getMessage(),
                'order_response' => $request,
            ]);
            return [
                'status' => false,
                'type' => 'alert',
                'msg' => 'Gagal !! Harap cek api provider log.',
            ];
        }
    }
}

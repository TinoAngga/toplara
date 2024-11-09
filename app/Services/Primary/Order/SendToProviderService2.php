<?php

namespace App\Services\Primary\Order;

use App\Jobs\OrderNotifyJob;
use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendToProviderService {
    public function handle($orderId){
        Log::info("dari cronjob");
        $order = Order::whereIn('id', [$orderId])
            ->with('user:id,username,level,email', 'payment:id,name', 'service:id,service_category_id,name')
            ->first();
        $provider = Provider::where('id', $order->provider_id)
            ->where('is_manual', 0)
            ->first();
        $service = Service::find($order->service_id);

        $headers = null; // SET DEFAULT HEADER

        // SET PARAMS
        if ($provider->id == 2) { // VIP GAME
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'order',
                'service' => $service->provider_service_code,
                'data_no' => $order->data,
                'data_zone' => $order->additional_data ?? null,
            ];
        } else if ($provider->id == 3) { // VIP PPOB
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'order',
                'service' => $service->provider_service_code,
                'data_no' => $order->data,
                'data_zone' => $order->additional_data ?? null,
            ];
        } else if ($provider->id == 5) {
            $params = [
                'success' => true
            ];
        } else {
            throw new Exception("Error Processing Request", 1);

        }
        // REQUEST ORDER TO PROVIDER
        DB::beginTransaction();
        try {
            $request = Curl::request('POST', $provider->api_url_order, $params, $headers);
            if ($request) {
                $response = json_decode($request, true);

                // SET RESPONSE
                $response_order_placed = false;
                $response_order_placed_message = null;
                $last_status = $order->status;
                // GET RESPONSE
                if ($provider->id == 2) { // VIP GAME
                    $response_order_placed = (isset($response['data']['trxid']) AND $response['data']['trxid']) ? $response['data']['trxid'] : false;
                    $response_order_placed_message = (isset($response['data']['message']) AND $response['data']['message']) ? $response['data']['message'] : null;
                } else if ($provider->id == 3) { // VIP PPOB
                    $response_order_placed = (isset($response['data']['trxid']) AND $response['data']['trxid']) ? $response['data']['trxid'] : false;
                    $response_order_placed_message = (isset($response['data']['message']) AND $response['data']['message']) ? $response['data']['message'] : $response['message'];
                }

                // CHECK RESPONSE
                if ($response_order_placed == false) {
                    Log::info($request);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'ORDER | ' . $response_order_placed_message,
                        'order_response' => $request,
                    ]);
                    $order->update([
                        'status' => 'gagal',
                    ]);
                    return [
                        'status' => false,
                        'type' => 'alert',
                        'msg' => 'Gagal !! Harap cek api provider log.',
                    ];
                } else {
                    // UPDATE ORDER
                    $order->update([
                        'status' => 'proses',
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
                OrderNotifyJob::dispatch($order)->delay(now()->addSeconds(30));
            } else {
                ProviderApiLog::create([
                    'provider_id' => $provider->id,
                    'order_id' => $order->id,
                    'description' => 'Gagal ketika melakukan permintaan order ke provider',
                    'order_response' => $request,
                ]);
                Log::info('Gagal ketika melakukan permintaan order ke provider ' . $provider->name . ' error curl request');
                return [
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Gagal !! Harap cek api provider log.',
                ];
            }
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
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

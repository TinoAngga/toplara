<?php

namespace App\Services\Primary\Order;

use App\Jobs\OrderNotifyJob;
use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;
use Exception;
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

        if (!$provider) {
            return [
                'status' => false,
                'type' => 'alert',
                'msg' => 'Provider not found !!.',
            ];
        }
        $service = Service::find($order->service_id);

        $headers = null; // SET DEFAULT HEADER

        // SET PARAMS
        if ($provider->id == 2) { // DIGI GAME
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'username' => $provider->api_username,
                'buyer_sku_code' => $service->provider_service_code,
                'customer_no' => ($order->additional_data <> '') ? $order->data . $order->additional_data : $order->data,
                'ref_id' => $order->invoice,
                'sign' => md5($provider->api_username . $provider->api_key . $order->invoice)
            ]);
        } else if ($provider->id == 3) { // DIGI PPOB
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'username' => $provider->api_username,
                'buyer_sku_code' => $service->provider_service_code,
                'customer_no' => ($order->additional_data <> '') ? $order->data . $order->additional_data : $order->data,
                'ref_id' => $order->invoice,
                'sign' => md5($provider->api_username . $provider->api_key . $order->invoice)
            ]);
        } else if ($provider->id == 4) {
            $headers = ['x-api-key: ' . $provider->api_key];
            $params = [
                'action' => 'order',
                'machine_id' => $provider->api_username,
                'item' => $service->provider_service_code,
                'target' => $order->data
            ];
        // } else if ($provider->id == 5) {
        //     $params = [
        //         'success' => true
        //     ];
        } else {
            throw new Exception("Error Processing Request", 1);

        }
        // REQUEST ORDER TO PROVIDER
        try {
            $request = Curl::request('POST', $provider->api_url_order, $params, $headers);
            if ($request) {
                $response = json_decode($request, true);

                // SET RESPONSE
                $response_order_placed = false;
                $response_order_placed_message = null;
                $last_status = $order->status;
                // GET RESPONSE
                if ($provider->id == 2) { // DIGI GAME
                    $response_order_placed = (isset($response['data']['status']) AND in_array($response['data']['status'], ['Pending', 'Sukses'])) ? $response['data']['ref_id'] : false;
                    $response_order_placed_message = (isset($response['data']['message']) AND $response['data']['message']) ? $response['data']['message'] : 'Connection Fails!';
                } else if ($provider->id == 3) { // DIGI PPOB
                    $response_order_placed = (isset($response['data']['status']) AND in_array($response['data']['status'], ['Pending', 'Sukses'])) ? $response['data']['ref_id'] : false;
                    $response_order_placed_message =  (isset($response['data']['message']) AND $response['data']['message']) ? $response['data']['message']  : 'Connection Fails!';
                } else if ($provider->id == 4) { // HDISLAND
                    $response_order_placed = (isset($response['status']) AND $response['status'] == true) ? strtoupper(uniqid('GOSTSHOPID')) : false;
                    $response_order_placed_message = (isset($response['message']) AND $response['message']) ? $response['message'] : 'Connection Fails!';
                    if ($response_order_placed_message <> '' AND $response_order_placed_message == 'Success') $response_order_placed_message .= ' - ' . ($response['data'] ?? 'SN ' . time());
                }

                // CHECK RESPONSE
                if ($response_order_placed == false) {
                    Log::error('Response order false result ketika melakukan permintaan status ke provider ' . $provider->name . ' | '  . $order->invoice . ' error response result false => ' . $request);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'ORDER | ' . $response_order_placed_message,
                        'order_response' => $request,
                    ]);
                    if ($provider->id == 4) {
                        $set_status = 'pending';
                    } else {
                        $set_status = 'gagal';
                    }
                    $order->update([
                        'status' => $set_status,
                    ]);
                    return [
                        'status' => false,
                        'type' => 'alert',
                        'msg' => 'Gagal !! Harap cek api provider log.',
                    ];
                } else {
                    // UPDATE ORDER
                    if ($provider->id == 4) {
                        $order_update_data = [
                            'status' => 'sukses',
                            'provider_order_id' => $response_order_placed,
                            'provider_order_description' => $response_order_placed_message,
                        ];
                    } else {
                        $order_update_data = [
                            'status' => 'proses',
                            'provider_order_id' => $response_order_placed,
                            'provider_order_description' => $response_order_placed_message ?? 'Proses...',
                        ];
                    }
                    $order->update($order_update_data);
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
                Log::error('Request order false result ketika melakukan permintaan order ke provider ' . $provider->name . ' | ' . $order->invoice . ' error response result false => ' . $request);
                return [
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Gagal !! Harap cek api provider log.',
                ];
            }
        } catch (\Throwable $e) {
            Log::info('Order error catch send to provider ' . $provider->name . ' | ' .$order->invoice . ' ' . $e->getMessage());
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

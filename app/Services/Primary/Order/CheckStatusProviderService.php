<?php

namespace App\Services\Primary\Order;

use App\Jobs\OrderNotifyJob;
use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class CheckStatusProviderService {
    public function handle($orderId){
        $order = Order::whereIn('id', [$orderId])
            ->with('user:id,username,level,email', 'payment:id,name', 'service:id,service_category_id,name')
            ->first();
            
        $provider = Provider::active()
            ->whereIn('id', [$order->provider_id])
            ->where('is_manual', 0)
            ->first();

        if (!$provider) {
            return [
                'status' => false,
                'type' => 'alert',
                'msg' => 'Provider not found !!.',
            ];
        }
        $headers = null; // SET DEFAULT HEADER

        $response_status = true;
        $response_status_message = null;

        // SET PARAMS
        if ($provider->id == 2) { // DIGI GAME
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'username' => $provider->api_username,
                'buyer_sku_code' => $order->service->provider_service_code,
                'customer_no' => ($order->additional_data <> '') ? $order->data . $order->additional_data : $order->data,
                'ref_id' => $order->invoice,
                'sign' => md5($provider->api_username . $provider->api_key . $order->invoice)
            ]);
        } else if ($provider->id == 3) { // DIGI PPOB
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'username' => $provider->api_username,
                'buyer_sku_code' => $order->service->provider_service_code,
                'customer_no' => ($order->additional_data <> '') ? $order->data . $order->additional_data : $order->data,
                'ref_id' => $order->invoice,
                'sign' => md5($provider->api_username . $provider->api_key . $order->invoice)
            ]);
        } else {
            $response_status = false;
            $response_status_message = null;
        }
        // REQUEST ORDER TO PROVIDER
        try {
            $request = Curl::request('POST', $provider->api_url_status, $params, $headers);
            if ($request) {
                $response = json_decode($request, true);

                // SET RESPONSE
                $last_status = $order->status;
                $response_result = (isset($response['result']) AND $response['result'] == true) ? true : false;
                $response_status = false;
                $response_status_message = null;
                // GET RESPONSE
                if ($provider->id == 2) { // DIGI GAME
                    $response_status = (isset($response['data']['status']) AND in_array(strtolower($response['data']['status']), ['pending', 'sukses', 'gagal'])) ? $response['data']['status'] : false;
                    $response_status_message = (isset($response['data']['sn']) AND $response['data']['sn']) ? $response['data']['sn'] : $response['data']['message'];
                } else if ($provider->id == 3) { // DIGI PPOB
                    $response_status = (isset($response['data']['status']) AND in_array(strtolower($response['data']['status']), ['pending', 'sukses', 'gagal'])) ? $response['data']['status'] : false;
                    $response_status_message = (isset($response['data']['sn']) AND $response['data']['sn']) ? $response['data']['sn'] : $response['data']['message'];
                } else {
                    $response_status = false;
                    $response_status_message = 'ORDER_NOT_VALID';
                }
                // CHECK RESPONSE
                if ($response_result == false) {
                    Log::error('Response status false result ketika melakukan permintaan status ke provider ' . $provider->name . ' | '  . $order->invoice . ' error response result false => ' . $request);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'STATUS | ' . $response_status_message,
                        'status_response' => $request,
                    ]);
                } else {
                    $order->update([
                        'provider_order_description' => $response_status_message,
                        'status' => getStatusInArray($response_status) == 'pending' ? 'process' : getStatusInArray($response_status),
                    ]);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'STATUS | ' . $response_status_message,
                        'status_response' => $request,
                    ]);
                }
                // ORDER NOTIFY
                if (in_array($order->status, ['sukses', 'gagal']) AND $last_status <> $order->status) {
                    OrderNotifyJob::dispatch($order)->delay(now()->addSeconds(30));
                }
            } else {
                ProviderApiLog::create([
                    'provider_id' => $provider->id,
                    'order_id' => $order->id,
                    'description' => 'Gagal ketika melakukan permintaan status ke provider',
                    'status_response' => $request,
                ]);
                Log::error('Request status false result ketika melakukan permintaan status ke provider ' . $provider->name . ' | ' . $order->invoice . ' error response result false => ' . $request);
            }
            return true;
        } catch (\Throwable $e) {
            Log::info('Order status catch send to provider ' . $provider->name . ' | ' .$order->invoice . ' ' . $e->getMessage());
            ProviderApiLog::create([
                'provider_id' => $provider->id,
                'order_id' => $order->id,
                'description' => $e->getMessage(),
                'status_response' => $request,
            ]);
            return true;
        }
    }
}

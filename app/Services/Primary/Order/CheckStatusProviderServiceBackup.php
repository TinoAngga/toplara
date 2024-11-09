<?php

namespace App\Services\Primary\Order;

use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\Log;

class CheckStatusProviderServiceBackup {
    public function handle($order){
        $provider = Provider::active()
            ->where('id', $order->provider_id)
            ->where('is_manual', 0)
            ->first();
        $service = Service::find($order->service_id);

        $headers = null; // SET DEFAULT HEADER

        // SET PARAMS
        if ($provider->id == 2) { // ATLANTIC
            $params = [
                'key' => $provider->api_key,
                'action' => 'status',
                'trxid' => $order->provider_order_id,
            ];
        } else if ($provider->id == 3) { // DIGIFLAZZ
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'username' => $provider->api_username,
                'buyer_sku_code' => $service->provider_service_code,
                'customer_no' => $order->data,
                'ref_id' => $order->invoice,
                'sign' => md5($provider->api_username . $provider->api_key . $order->invoice)
            ]);
        } else if ($provider->id == 4) { // VIP PAYMENT
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'status',
                'trxid' => $order->provider_order_id,
            ];
        }
        // REQUEST ORDER TO PROVIDER
        try {
            $request = Curl::request('POST', $provider->api_url_status, $params, $headers);
            if ($request) {
                $response = json_decode($request, true);

                // SET RESPONSE
                $responseFromProvider = [
                    'success' => false,
                    'provider_status'=> null,
                    'provider_success_message' => null,
                    'provider_error_message' => null,
                ];
                // GET RESPONSE
                if ($provider->id == 2) { // ATLANTIC
                    if (isset($response['data']['status']) AND $response['data']['status']) {
                        $responseFromProvider['success'] = true;
                    }
                    $responseFromProvider['provider_status'] = $response['data']['status'] ?? $order->status;
                    $responseFromProvider['provider_success_message'] = $response['data']['message'] ?? $response['data'];
                    $responseFromProvider['provider_error_message'] = $response['data']['message'] ?? $response['data'];
                } elseif ($provider->id == 3) { // DIGIFLAZZ
                    if (isset($response['data']['status']) AND in_array(strtolower($response['data']['status']), ['sukses', 'pending'])) {
                        $responseFromProvider['success'] = true;
                    }
                    $responseFromProvider['provider_status'] = $response['data']['status'] ?? $order->status;
                    $responseFromProvider['provider_success_message'] = $response['data']['sn'] ?? $response['data']['message'];
                    $responseFromProvider['provider_error_message'] = $response['data']['message'];
                }

                // CHECK RESPONSE
                if ($responseFromProvider['success'] == false) {
                    Log::error($request);
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'STATUS | ' . $responseFromProvider['provider_error_message'],
                        'status_response' => $request,
                    ]);
                } else {
                    Order::find($order->id)->update([
                        'provider_order_description' => $responseFromProvider['provider_success_message'],
                        'status' => getStatusInArray($responseFromProvider['provider_status']),
                    ]);
                    dispatch(new OrderNotifyJob($order->with('payment', 'user', 'service')->find($order->id)))->delay(now()->addSeconds(2));
                    ProviderApiLog::create([
                        'provider_id' => $provider->id,
                        'order_id' => $order->id,
                        'description' => 'STATUS | ' . $responseFromProvider['provider_success_message'],
                        'status_response' => $request,
                    ]);
                }
            } else {
                ProviderApiLog::create([
                    'provider_id' => $provider->id,
                    'order_id' => $order->id,
                    'status' => 'status_failed',
                    'description' => 'Gagal ketika melakukan permintaan status ke provider',
                    'status_response' => $request,
                ]);
                Log::error('Gagal ketika melakukan permintaan status ke provider ' . $provider->name . ' error curl request');
            }
            return true;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
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

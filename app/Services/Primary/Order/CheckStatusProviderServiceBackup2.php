<?php

namespace App\Services\Primary\Order;

use App\Jobs\OrderNotifyJob;
use App\Libraries\Curl;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderApiLog;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
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
        $headers = null; // SET DEFAULT HEADER

        $response_status = true;
        $response_status_message = null;

        // SET PARAMS
        if ($provider->id == 2) { // INDOCUAN GAME
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'status',
                'trxid' => $order->provider_order_id,
            ];
        } else if ($provider->id == 3) { // INDOCUAN PPOB
            $params = [
                'key' => $provider->api_key,
                'sign' => md5($provider->api_username . $provider->api_key),
                'type' => 'status',
                'trxid' => $order->provider_order_id,
            ];
        } else {
            $response_status = false;
            $response_status_message = null;
        }
        // REQUEST ORDER TO PROVIDER
        DB::beginTransaction();
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
                if ($provider->id == 2) { // INDOCUAN GAME
                    $response_status = (isset($response['data'][0]['status']) AND $response['data'][0]['status']) ? $response['data'][0]['status'] : 'proses';
                    $response_status_message = (isset($response['data'][0]['note']) AND $response['data'][0]['note']) ? $response['data'][0]['note'] : $response['message'];


                } else if ($provider->id == 3) { // INDOCUAN PPOB
                    $response_status = (isset($response['data'][0]['status']) AND $response['data'][0]['status']) ? $response['data'][0]['status'] : 'proses';
                    $response_status_message = (isset($response['data'][0]['note']) AND $response['data'][0]['note']) ? $response['data'][0]['note'] : $response['message'];


                } else {
                    $response_status = false;
                    $response_status_message = 'ORDER_NOT_VALID';
                }
                // CHECK RESPONSE
                if ($response_result == false) {
                    Log::info($request);
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
                Log::info($request);
                Log::info('Gagal ketika melakukan permintaan status ke provider ' . $provider->name . ' error curl request');
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
            DB::rollBack();
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

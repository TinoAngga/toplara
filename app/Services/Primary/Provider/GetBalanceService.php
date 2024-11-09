<?php

namespace App\Services\Primary\Provider;

use Illuminate\Support\Facades\Http;
use App\Libraries\CustomException;
use App\Libraries\Curl;
use Exception;
use Illuminate\Support\Facades\Log;

class GetBalanceService
{
    public function handle(Object $provider){
        if($provider->is_manual == 1) throw new CustomException([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Provider ini tidak memiliki akses untuk mengirim permintaan api.'
        ]);
        $headers = [];
        if ($provider->id == 2) { // DIGI GAME
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'cmd' => 'deposit',
                'username' => $provider->api_username,
                'sign' => md5($provider->api_username . $provider->api_key . 'depo')
            ]);
        } elseif ($provider->id == 3) { // DIGI PULSA
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'cmd' => 'deposit',
                'username' => $provider->api_username,
                'sign' => md5($provider->api_username . $provider->api_key . 'depo')
            ]);
        }
        try {
            $request = Curl::request('POST', $provider->api_url_profile, $params, $headers);
            if ($request) {
                $json_result = json_decode($request, true);
                $provider->api_balance = $this->_processing_data_set($json_result, $provider->id);
                $provider->save();
            } else {
                throw new CustomException([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Permintaan api gagal !!.'
                ]);
            }
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
            throw new CustomException([
                'status' => false,
                'type' => 'alert',
                'msg' => $e->getMessage()
            ]);
        }
    }
    protected function _processing_data_set(Array $array, Int $provider_id){
        if ($provider_id === 2) { // DIGI GAME
            if (isset($array['data']['rc'])){
                Log::info('Request get balance digi game' . json_encode($array));
                throw new Exception('Permintaan api gagal (KESALAHAN SISTEM REQPROVIDER) !!.');
            }
            return $array['data']['deposit'];
        } else if ($provider_id === 3) { // DIGI PPOB
            if (isset($array['data']['rc'])){
                Log::info('Request get balance digi ppob' . json_encode($array));
                throw new Exception('Permintaan api gagal (KESALAHAN SISTEM REQPROVIDER) !!.');
            }
            return $array['data']['deposit'];
        }
        throw new \Exception('Permintaan api gagal (KESALAHAN SISTEM REQPROVIDER) !!.');
    }
}

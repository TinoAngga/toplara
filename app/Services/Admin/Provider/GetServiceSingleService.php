<?php

namespace App\Services\Admin\Provider;

use Illuminate\Support\Facades\Http;

class GetServiceSingleService
{
    public function handle($provider, $provider_service_code){
        $params = null;
        if ($provider->id == '2') {
            $params = 'key='.$provider->api_key.'&action=services';
        }
        try {
            $request = Http::post($provider->api_url_service . '?' . $params);
            if ($request->ok() === true) {
                $response = $request->getBody()->getContents();
                $json_result = json_decode($response, true);
                if ($provider->id == '2') {
                    return $this->_array_atlantic($json_result, $provider_service_code);
                }
            } else {
                return 'false';
            }
        } catch (\Throwable $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }
    protected function _array_atlantic(Array $array, String $provider_service_code){
        if ($array['result'] == false) {
            return false;
        }
        $arr = [];
        for ($i = 0; $i < count($array['data']); $i++) {
            if ($array['data'][$i]['code'] == $provider_service_code) {
                $arr['provider_service_code'] = $array['data'][$i]['code'];
                $arr['name'] = $array['data'][$i]['name'];
                $arr['price'] = $array['data'][$i]['price'];
                $arr['status'] = ($array['data'][$i]['status'] == 'available') ? 'on' : 'off';
            }
        }
        return $arr;
    }
}

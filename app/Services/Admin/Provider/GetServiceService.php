<?php

namespace App\Services\Admin\Provider;

use Illuminate\Support\Facades\Http;

class GetServiceService
{
    public function handle($provider, $service_category){
        $params = null;
        if ($provider->id == 2) { // ATLANTIC
            $params = 'key='.$provider->api_key.'&action=services';
        }
        try {
            $request = Http::post($provider->api_url_service . '?' . $params);
            if ($request->ok() === true) {
                $response = $request->getBody()->getContents();
                $json_result = json_decode($response, true);
                if ($provider->id == 2) { // ATLANTIC
                    return $this->_array_atlantic($json_result, $service_category);
                }
            } else {
                return 'false';
            }
        } catch (\Throwable $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }
    protected function _array_atlantic(Array $array, $service_category){

        if ($array['result'] == false) {
            return false;
        }
        $result = [];
        for ($i = 0; $i < count($array['data']); $i++) {
            if ($array['data'][$i]['brand'] == $service_category) {
                $arr['provider_service_code'] = $array['data'][$i]['code'];
                $arr['name'] = $array['data'][$i]['name'];
                $arr['price'] = $array['data'][$i]['price'];
                $arr['status'] = ($array['data'][$i]['status'] == 'available') ? 'on' : 'off';
                array_push($result, $arr);
            }
        }
        return $result;
    }
}

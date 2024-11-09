<?php

namespace App\Services\Admin\Provider;

use Illuminate\Support\Facades\Http;

class ServiceCategoryService
{
    public function handle($provider){
        $params = null;
        if ($provider->id == '2') { // ATLANTIC
            $params = 'key='.$provider->api_key.'&action=services';
        }
        try {
            $request = Http::post($provider->api_url_service . '?' . $params);
            if ($request->ok() === true) {
                $response = $request->getBody()->getContents();
                $json_result = json_decode($response, true);
                if ($provider->id == '2') { // ATLANTIC
                    return $this->_array_atlantic($json_result);
                }
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }
    protected function _array_atlantic(Array $array){
        if ($array['result'] == false) {
            \Log::info($array);
            return false;
        }
        $list = '<option value="">Pilih...</option>,';
        foreach ($array['data'] as $key => $value) {
            if ($value['type'] == 'voucher-game') {
                $list .= '<option value="'.$value['brand'].'">'.$value['brand'].'</option>,';
            }
        }
        return ['status' => true, 'data' => implode('', array_unique(explode(',', $list)))];
    }
}

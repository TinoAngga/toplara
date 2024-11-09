<?php

namespace App\Services\Primary\Provider;

use App\Libraries\CustomException;
use App\Libraries\Curl;
use Exception;
use Illuminate\Support\Facades\Log;

class ServiceGetService
{
    public function handle(Object $provider, String $type, String $additionalRequest = null){
        if($provider->is_manual == 1) throw new CustomException([
            'status' => false,
            'type' => 'alert',
            'msg' => 'Provider ini tidak memiliki akses untuk mengirim permintaan api.'
        ]);

        $headers = []; // SET DEFAULT HEADER AS NULL
        if ($provider->id == 2) { // DIGIFLAZZ GAME
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'cmd' => 'prepaid',
                'username' => $provider->api_username,
                'sign' => md5($provider->api_username . $provider->api_key . 'pricelist')
            ]);
        } else if ($provider->id == 3) { // DIGIFLAZZ PPOB
            $headers = ['Content-Type: application/json'];
            $params = json_encode([
                'cmd' => 'prepaid',
                'username' => $provider->api_username,
                'sign' => md5($provider->api_username . $provider->api_key . 'pricelist')
            ]);
        }
        try {
            $request = Curl::request('POST', $provider->api_url_service, $params, $headers);
            if ($request) {
                $json_result = json_decode($request, true);
                return $this->_processing_data_set($json_result, $type, $provider->id, $additionalRequest);
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
    protected function _processing_data_set(Array $array, String $type, Int $providerId, String $additionalRequest = null){
        if ($providerId == 2) { // DIGIFLAZZ GAME
            if (isset($array['data']['rc']) || !isset($array['data'][0]['brand'])){
                Log::info('Request get service digiflazz game' . json_encode($array));
                throw new Exception('Permintaan api gagal (KESALAHAN SISTEM REQPROVIDER) !!.');
            }
        } else if ($providerId == 3) { // DIGIFLAZZ PPOB
            if (isset($array['data']['rc']) || !isset($array['data'][0]['brand'])){
                Log::info('Request get service digiflazz ppob' . json_encode($array));
                throw new Exception('Permintaan api gagal (KESALAHAN SISTEM REQPROVIDER) !!.');
            }
        }

        switch ($type) {
            case 'GET_CATEGORY':
                if ($providerId == 2) { // DIGIFLAZZ GAME
                    $list = '<option value="">Pilih...</option>,';
                    foreach ($array['data'] as $key => $value) {
                        if (strtolower($value['category']) == 'games') {
                            $list .= '<option value="'.$value['brand'].'">'.$value['brand'].'</option>,';
                        }
                    }
                    return implode('', array_unique(explode(',', $list)));
                }
            case 'GET_ALL_BY_CATEGORY':
                if ($providerId == 2) { // DIGIFLAZZ GAME
                    $result = [];
                    for ($i = 0; $i < count($array['data']); $i++) {
                        if (strtolower($array['data'][$i]['brand']) == strtolower($additionalRequest)) {
                            $arr['provider_service_code'] = $array['data'][$i]['buyer_sku_code'];
                            $arr['name'] = $array['data'][$i]['product_name'];
                            $arr['price'] = $array['data'][$i]['price'];
                            $arr['status'] = ($array['data'][$i]['buyer_product_status'] == true) ? 'on' : 'off';
                            array_push($result, $arr);
                        }
                    }
                    return $result;
                }
            case 'GET_SINGLE':
                if ($providerId == 2) { // DIGIFLAZZ GAME
                    $arr = [];
                    for ($i = 0; $i < count($array['data']); $i++) {
                        if ($array['data'][$i]['buyer_sku_code'] == $additionalRequest) {
                            $arr['provider_service_code'] = $array['data'][$i]['buyer_sku_code'];
                            $arr['name'] = $array['data'][$i]['product_name'];
                            $arr['price'] = $array['data'][$i]['price'];
                            $arr['status'] = ($array['data'][$i]['buyer_product_status'] == true) ? 'on' : 'off';
                        }
                    }
                    return $arr;
                }
            case 'GET_ALL':
                if ($providerId == 2) { // DIGIFLAZZ GAME
                    // $result = [];
                    // for ($i = 0; $i < count($array['data']); $i++) {
                    //     $arr['category'] = $array['data'][$i]['game'];
                    //     $arr['provider_service_code'] = $array['data'][$i]['code'];
                    //     $arr['name'] = $array['data'][$i]['name'];
                    //     $arr['price'] = $array['data'][$i]['price']['special'];
                    //     $arr['status'] = ($array['data'][$i]['status'] == 'available') ? 'on' : 'off';
                    //     array_push($result, $arr);
                    // }
                    return $array['data'];
                } else if ($providerId == 3) { // DIGIFLAZZ PPOB
                    // $result = [];
                    // for ($i = 0; $i < count($array['data']); $i++) {
                    //     $arr['type'] = $array['data'][$i]['type'];
                    //     $arr['category'] = $array['data'][$i]['brand'];
                    //     $arr['sub_category'] = $arr['data'][$i]['category'];
                    //     $arr['provider_service_code'] = $array['data'][$i]['code'];
                    //     $arr['name'] = $array['data'][$i]['name'];
                    //     $arr['price'] = $array['data'][$i]['price']['special'];
                    //     $arr['description'] = $array['data'][$i]['note'];
                    //     $arr['status'] = ($array['data'][$i]['status'] == 'available') ? 'on' : 'off';
                    //     array_push($result, $arr);
                    // }
                    return $array['data'];
                }
            default:
                throw new CustomException([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Aksi tidak di temukan.'
                ]);
                break;
        }


    }
}

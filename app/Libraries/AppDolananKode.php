<?php

namespace App\Libraries;

use App\Models\Provider;

class AppDolananKode {

    protected $apiKey, $machineId;
    protected $baseUrl = 'https://app.dolanankode.web.id/api';
    // protected $baseUrl = 'http://localhost/api-hdi/api';

    public function __construct(){
        $provider = Provider::whereIn('id', [4])->first();
        if ($provider == null) {
            $this->apiKey = '7b4a2771a556c57b81cce2702f339a7b';
            $this->machineId = '3aff64fe3401a304fc40e4d98c55d3a3';
        } else {
            $this->apiKey = $provider->api_key;
            $this->machineId = $provider->api_username;
        }
    }

    public function checkNickname(
        string $code,
        string $data,
        ?string $additionalData = null,
    ) {
        $url = $this->baseUrl.'/game/check-nickname';
        $post = [
            'machine_id' => $this->machineId,
            'code' => $code,
            'data' => $data,
            'additionalData' => $additionalData ?? ''
        ];
        $headers = [
            'x-api-key: '.$this->apiKey
        ];
        $request = $this->curl($url, $post, $headers, true, 'POST');
        $response = json_decode($request, true);
        if (!$response) {
            return [
                'status' => false,
                'msg' => 'Failed to connect to server'
            ];
        }
        if (isset($response['status']) && $response['status'] == false) {
            return [
                'status' => false,
                'msg' => $response['message']
            ];
        }
        return  [
            'status' => true,
            'msg' => $response['message'],
            'data' => $response['data']
        ];
    }

    public function order(
        $machineId,
        $item,
        $target
    ) {
        $url = $this->baseUrl.'/hdi/action';
        $post = [
            'machine_id' => $machineId,
            'action' => 'order',
            'item' => $item,
            'target' => $target
        ];
        $headers = [
            'x-api-key: '.$this->apiKey
        ];
        $request = $this->curl($url, $post, $headers, true, 'POST');
        $response = json_decode($request, true);
        if (!$response) {
            return [
                'status' => false,
                'msg' => 'Failed to connect to server'
            ];
        }
        if (isset($response['status']) && $response['status'] == false) {
            return [
                'status' => false,
                'msg' => $response['message']
            ];
        }
        return  [
            'status' => true,
            'msg' => $response['message'],
            'data' => $response['data']
        ];
    }

    protected function curl(
        string $url,
        array $post,
        array $headers,
        bool $follow = false,
        ?string $method = null
    ) {
        $_post = [];
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name.'='.urlencode($value);
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($follow == true) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if ($method !== null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (is_array($post)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            }
        }
        $result = curl_exec($ch);
        $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        return $body;
    }
}

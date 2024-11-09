<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Log;
// use Xendit\Xendit as XenditLib;

class Xendit {

    protected $apiKey;
    protected $baseUrl;

    public function __construct(String $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.xendit.co/';
    }

    // public function createQRCodes(Array $params)
    // {
    //     XenditLib::setApiKey('SECRET_API_KEY');
    //     $qr_code = \XenditLib\QRCode::create($params);
    //     Log::info(var_dump($qr_code));
    // }
    public function createQRCodes(Array $params){
        $method = 'POST';
        $end_point = $this->baseUrl . 'qr_codes';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':')
        ];
        $request = $this->request($method, $end_point, json_encode($params), $headers);
        $result = json_decode($request['body'], true);
        if ($request['code'] == 200) {
            return [
                'status' => true,
                'data' => $result,
                'json' => $request['body']
            ];
        }
        Log::info($request['body']);
        return false;
    }
    public function createVA(Array $params){
        $method = 'POST';
        $end_point = $this->baseUrl . 'callback_virtual_accounts';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':')
        ];
        Log::info(json_encode($params));
        $request = $this->request($method, $end_point, json_encode($params), $headers);
        $result = json_decode($request['body'], true);
        if ($request['code'] == 200) {
            return [
                'status' => true,
                'data' => $result,
                'json' => $request['body']
            ];
        }
        Log::info($request['body']);
        return false;
    }
    public function getChannels()
    {
        $method = 'POST';
        $end_point = $this->baseUrl . 'available_virtual_account_banks';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':')
        ];
        $request = $this->request($method, $end_point, null, $headers);
    }
    public function request(String $method = '', String $end_point = '', $params = '', Array $headers = null): Array
    {
		$_post = [];
		if (is_array($params)) {
			foreach ($params as $name => $value) {
				$_post[] = $name.'='.urlencode($value);
			}
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $end_point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
		curl_setopt($ch, CURLOPT_TIMEOUT, 240); // 4 mnt
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if ($headers <> '') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if ($params <> '') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			if (is_array($params)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			}
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		$result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return [
            'code' => $http_code,
            'body' => $result
        ];
    }
}

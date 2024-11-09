<?php
namespace App\Libraries;

class Paydisini {

    protected $api_key;
    protected $base_url;

    public function __construct(String $api_key) {
        $this->api_key = $api_key;
        $this->base_url = 'https://paydisini.co.id/api/';
    }

    public function create(array $data = [])
    {
        $method = 'POST';
        $end_point = $this->base_url;
        $params = [
            'key'               => $this->api_key,
            'request'           => 'new',
            'unique_code'       => $data['unique_code'],
            'service'           => $data['service_code'],
            'amount'            => $data['amount'],
            'note'              => $data['note'],
            'valid_time'        => $data['valid_time'] ?? 7200,
            'ewallet_phone'     => $data['phone'],
            'type_fee'          => $data['type_fee'] ?? 1,
            'signature'         => md5($this->api_key . $data['unique_code'] . $data['service_code'] . $data['amount'] . $data['valid_time'] . 'NewTransaction')
        ];
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.5249.91 Safari/537.36'
        ];
        // print json_encode($params, JSON_PRETTY_PRINT);
        // exit;
        $request = $this->request($method, $end_point, $params, $headers);
        $response = json_decode($request, true);
        if ($response['success'] == false) {
            \Log::info('[ REQUEST PAYDISINI ] ' . $request);
            return false;
        } else {
            return [
                'status' => true,
                'curl' => $request,
                'data' => $response['data']
            ];
        }
    }
    
    
    public function getProfile()
{
    $method = 'POST';
    $end_point = 'https://paydisini.co.id/api/'; // Endpoint baru yang diberikan
    $params = [
        'key' => $this->api_key,
        'request' => 'profile',
        'signature' => md5($this->api_key . 'profile') // Pastikan signature dihasilkan secara dinamis
    ];
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.5249.91 Safari/537.36'
    ];

    $request = $this->request($method, $end_point, $params, $headers);
    $response = json_decode($request, true);

    if (isset($response['success']) && $response['success'] == false) {
        \Log::info('[ REQUEST PAYDISINI ] ' . $request);
        return [
            'status' => false
        ];
    } else {
        return [
            'status' => true,
            'data' => $response['data'] ?? []
        ];
    }
}


    public static function request(String $method = '', String $end_point = '', $params = null, Array $headers = null): String
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
        // var_dump($result);
        // exit;
		if (curl_errno($ch) != 0 && empty($result) && $http_code !== 200) {
			return false;
		}
		curl_close($ch);
		return $result;
    }
}

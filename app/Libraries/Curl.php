<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Log;

class Curl
{
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
		if (curl_errno($ch) != 0 && empty($result) && $http_code !== 200) {
            \Log::error('Curl Error with status code: ' . var_dump($http_code) . '  | ' . var_dump($result));
			return false;
		}
		curl_close($ch);
		return $result;
    }
    public static function requestV2(String $method = '', String $end_point = '', Array $post = [], Array $headers = []): String
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $end_point,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($post)
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
        return $response;
    }
}


<?php

namespace App\Libraries;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Log;

class LinkQU {

    public $baseUrl;

    protected $baseUrlDev = 'https://gateway-dev.linkqu.id';
    protected $baseUrlProd = 'https://gateway.linkqu.id';

    protected $clientId, $clientSecret, $clientUsername, $clientPIN, $clientServerKey;

    public function __construct($modeDev = true) {
        if ($modeDev) $this->baseUrl = $this->baseUrlDev;
        else $this->baseUrl = $this->baseUrlProd;

        $this->clientId = getConfig('linkqu_client_id');
        $this->clientSecret = getConfig('linkqu_client_secret');
        $this->clientUsername = getConfig('linkqu_client_username');
        $this->clientPIN = getConfig('linkqu_client_pin');
        $this->clientServerKey = getConfig('linkqu_client_server_key');

    }

    public function setExpired(int $minute, string $format = 'YmdHis')
    {
        $date = new DateTime();
        return $date->setTimestamp(time() + ($minute * 60))
            ->setTimezone(new DateTimeZone('Asia/Jakarta'))
            ->format($format);
    }

    public function createInvoice($code, $data)
    {
        $code = strtolower($code);
        if ($code == 'vapermata') return $this->createVAPermata($data);
        if ($code == 'qris') return $this->createQr($data);
        if (in_array($code, ['014', '002', '022', '009', '008', '016', '013', '011', '451', '490'])) return $this->createVA($code, $data);
        return false;
    }

    public function createQr($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $path = '/transaction/create/qris';
        $endpoint = $this->baseUrl . '/linkqu-partner/transaction/create/qris';
        $method = 'POST';
        // $expiredAt = strtotime(date('YmdHis', strtotime('+1 day')));
        $expiredAt = $this->setExpired(60);
        $secondvalue = strtolower(preg_replace('/[^0-9a-zA-Z]/', '', $data['amount'] . $expiredAt . $data['partner_reff'] . $data['customer_id'] . $data['customer_name'] . $data['customer_email'] . $this->clientId));
        $signToString = $path.$method.$secondvalue;
        $signature = hash_hmac('SHA256', $signToString , $this->clientServerKey);
        $params = json_encode([
            'amount' => $data['amount'],
            'partner_reff' => $data['partner_reff'],
            'customer_id' => $data['customer_id'],
            'customer_name' => $data['customer_name'],
            'expired' => $expiredAt,
            'username' => $this->clientUsername,
            'pin' => $this->clientPIN,
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'signature' => $signature,
        ]);
        $headers = [
            'client-id: ' . $this->clientId,
            'client-secret: ' . $this->clientSecret
        ];

        $request = $this->request($endpoint, $method, $params, $headers);
        $response = json_decode($request, true);
        if (isset($response['status']) && $response['status'] == 'SUCCESS') {
            return [
                'status' => true,
                'data' => $response,
                'json' => $request
            ];
        }
        return false;
    }

    public function createVAPermata($params)
    {

    }

    public function createVA($code, $data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $path = '/transaction/create/va';
        $endpoint = $this->baseUrl . '/linkqu-partner/transaction/create/va';
        $method = 'POST';
        $expiredAt = $this->setExpired(60);
        $bankCode = $code;
        $secondvalue = strtolower(preg_replace('/[^0-9a-zA-Z]/', '', $data['amount'] . $expiredAt . $bankCode . $data['partner_reff'] . $data['customer_id'] . $data['customer_name'] . $data['customer_email'] . $this->clientId));
        $signToString = $path.$method.$secondvalue;
        $signature = hash_hmac('sha256', $signToString , $this->clientServerKey);
        $params = json_encode([
            'amount' => $data['amount'],
            'partner_reff' => $data['partner_reff'],
            'customer_id' => $data['customer_id'],
            'customer_name' => $data['customer_name'],
            'expired' => $expiredAt,
            'username' => $this->clientUsername,
            'pin' => $this->clientPIN,
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'bank_code' => $bankCode,
            'signature' => $signature,
        ]);
        $headers = [
            'client-id: ' . $this->clientId,
            'client-secret: ' . $this->clientSecret
        ];

        $request = $this->request($endpoint, $method, $params, $headers);
        $response = json_decode($request, true);
        if (isset($response['partner_reff']) && $response['partner_reff']) {
            return [
                'status' => true,
                'data' => $response,
                'json' => $request
            ];
        }
        return false;
    }

    protected function request(string $endpoint, string $method, $params = null, array $headers)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $params,
            // CURLOPT_POSTFIELDS =>'{
            //     "amount" : 50000,
            //     "partner_reff" : "20091911581103688587",
            //     "customer_id" : "31857418",
            //     "customer_name" : "Gerbang Pembayaran Indonesia",
            //     "expired" : "20201123230000",
            //     "username" : "LI307GXIN",
            //     "pin" : "-------",
            //     "customer_phone" : "081231857418",
            //     "customer_email" : "cto@linkqu.id",
            //     "signature" : "751a5eeb119903f0239402367ca5f508e6603035692e3b1d8294d916e6735a68"
            // }',
            CURLOPT_HTTPHEADER => $headers,
            // CURLOPT_HTTPHEADER => array(
            //     'client-id: test',
            //     'client-secret: test213'
            // ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        Log::error('[ LINKQU ]' . $response);
        return $response;
    }
}

<?php
namespace App\Libraries;

use App\Libraries\Curl;

class Tripay extends Curl {

    protected $bearerKey;
    protected $tripayBaseUrl;

    public function __construct(String $bearerKey) {
        $this->bearerKey = $bearerKey;
        $this->tripayBaseUrl = 'https://tripay.co.id/api/';
    }

    public function closedPayment(Array $data = []): Array|Bool
    {
        $method = 'POST';
        $end_point = $this->tripayBaseUrl . 'transaction/create';
        $params = [
            'method'         => $data['method'],
            'merchant_ref'   => $data['merchant_ref'],
            'amount'         => $data['amount'],
            'customer_name'  => $data['customer']['name'],
            'customer_email' => $data['customer']['email'],
            'order_items'    => [
                [
                    'sku'         => $data['order_items']['sku'],
                    'name'        => $data['order_items']['name'],
                    'price'       => $data['amount'],
                    'quantity'    => 1,
                ]
            ],
            'return_url'   => $data['return_url'],
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $data['tripay_merchant_code'] . $data['merchant_ref'] . $data['amount'], $data['tripay_private_key'])
        ];
        $headers = [
            'Authorization: Bearer ' . $this->bearerKey
        ];

        $request = $this->requestV2($method, $end_point, $params, $headers);
        $response = json_decode($request, true);
        if ($response['success'] == false) {
            \Log::error($request);
            return false;
        } else {
            return [
                'status' => true,
                'curl' => $request,
                'data' => $response['data']
            ];
        }
    }

    public function openPayment(Array $data = []): Array|bool
    {
        $method = 'POST';
        $end_point = $this->tripayBaseUrl . 'transaction/create';
        $params = [
            'method'         => $data['bank_code'],
            'merchant_ref'   => $data['merchant_ref'],
            'amount'         => $data['amount'],
            'customer_name'  => $data['customer']['name'],
            'customer_email' => $data['customer']['email'],
            // 'customer_phone' => '081234567890',
            'order_items'    => [
                [
                    'sku'         => $data['order_items']['sku'],
                    'name'        => $data['order_items']['name'],
                    'price'       => $data['amount'],
                    'quantity'    => 1,
                ]
            ],
            'return_url'   => $data['return_url'],
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $data['tripay_merchant_code'] . $data['merchant_ref'] . $data['amount'], $data['tripay_private_key'])
        ];
        $headers = [
            'Authorization: Bearer ' . $this->bearerKey
        ];

        $request = $this->request($method, $end_point, $params, $headers);
        $response = json_decode($request, true);
        if ($response['success'] == false) {
            \Log::error($request);
            return false;
        } else {
            return [
                'status' => true,
                'curl' => $request,
                'data' => $response['data']
            ];
        }
    }
}

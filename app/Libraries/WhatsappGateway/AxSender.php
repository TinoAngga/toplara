<?php

namespace App\Libraries\WhatsappGateway;

use App\Libraries\Curl;
use Illuminate\Support\Facades\Log;

class AxSender extends Curl
{
    protected $apiKey;
    protected $apiBaseUrl = 'https://bot.tinoganteng.com';
    protected $senderNumber;

    public function __construct() {
        $this->apiKey = getConfig('whatsapp_gateway_api_key');
        $this->senderNumber = getConfig('whatsapp_gateway_sender_number');
    }

    public function sendWithTemplate(string $message, array $params = []): bool
    {
        $url = $this->apiBaseUrl . '/send-template';
        $headers = [
            'Content-Type: application/json'
        ];
        $postdata = json_encode([
            'api_key' => $this->apiKey,
            'sender' => $this->senderNumber,
            'number' => $params['target'],
            'message' => $message,
            'template' => $params['template'],
            'footer' => getConfig('title')
        ]);
        $request = $this->request('POST', $url, $postdata, $headers);
        $response = json_decode($request, true);
        Log::info('AxSender sendWithTemplate response: ' . $request . ' and params ' . $postdata);
        if (isset($response['status']) && $response['status'] == false) {
            return false;
        }
        return true;
    }

    public function sendMessage(string $message, array $params = []): bool
    {
        $url = $this->apiBaseUrl . '/send-message';
        $headers = [
            'Content-Type: application/json'
        ];
        $postdata = json_encode([
            'api_key' => $this->apiKey,
            'sender' => $this->senderNumber,
            'number' => $params['target'],
            'message' => $message,
        ]);
        $request = $this->request('POST', $url, $postdata, $headers);
        $response = json_decode($request, true);
        Log::info('AxSender sendMessage response: ' . $request . ' and params ' . $postdata);
        if (isset($response['status']) && $response['status'] == false) {
            return false;
        }
        return true;
    }
    
    public function sendMedia(string $caption, array $params = []): bool
    {
        if (!isset($params['media_type']) || !isset($params['url'])) {
            Log::error('AxSender sendMedia: Missing required parameters media_type or url');
            return false;
        }

        // Validate media type
        $validMediaTypes = ['image', 'video', 'audio', 'document'];
        if (!in_array($params['media_type'], $validMediaTypes)) {
            Log::error('AxSender sendMedia: Invalid media type ' . $params['media_type']);
            return false;
        }

        $url = $this->apiBaseUrl . '/send-media';
        $headers = [
            'Content-Type: application/json'
        ];

        $postdata = json_encode([
            'api_key' => $this->apiKey,
            'sender' => $this->senderNumber,
            'number' => $params['target'],
            'media_type' => $params['media_type'],
            'url' => $params['url'],
            'caption' => $caption
        ]);

        $request = $this->request('POST', $url, $postdata, $headers);
        $response = json_decode($request, true);
        Log::info('AxSender sendMedia response: ' . $request . ' and params ' . $postdata);

        if (isset($response['status']) && $response['status'] == false) {
            return false;
        }
        return true;
    }
}


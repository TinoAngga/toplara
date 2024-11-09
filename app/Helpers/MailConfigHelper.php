<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MailConfigHelper
{
    public static function getMailConfig()
    {
        // Ambil konfigurasi dari tabel website_configs
        $config = DB::table('website_configs')->where('config_group', 'mail')->pluck('config_value', 'config_key')->toArray();

        return [
            'username' => $config['mail_username'] ?? env('MAIL_USERNAME'),
            'password' => $config['mail_password'] ?? env('MAIL_PASSWORD'),
            'host' => $config['mail_host'] ?? env('MAIL_HOST'),
            'port' => $config['mail_port'] ?? env('MAIL_PORT'),
            'encryption' => $config['mail_encryption'] ?? env('MAIL_ENCRYPTION'),
            'from_address' => $config['mail_from_address'] ?? env('MAIL_FROM_ADDRESS'),
            'from_name' => $config['mail_from_name'] ?? env('MAIL_FROM_NAME'),
        ];
    }
}

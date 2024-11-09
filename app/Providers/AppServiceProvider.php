<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Html\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config(['mail.mailers.smtp.username' => getConfig('mail_username')]);
		config(['mail.mailers.smtp.password' => getConfig('mail_password')]);
		config(['mail.mailers.smtp.encryption' => getConfig('mail_encryption')]);
		config(['mail.mailers.smtp.port' => getConfig('mail_port')]);
		config(['mail.mailers.smtp.host' => getConfig('mail_host')]);
		config(['mail.from.address' => getConfig('mail_from')]);
		config(['mail.from.name' => getConfig('title')]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::useVite();
        Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            return substr($value, 0, 3) == '628';
        });
        Validator::extend('phone_number_ewallet', function ($attribute, $value, $parameters, $validator) {
            return substr($value, 0, 2) == '08';
        });
        Validator::extend('min_amount', function ($attribute, $value, $parameters, $validator) {
            return $value > 9999;
        });
        Validator::extend('email', function ($attribute, $value, $parameters, $validator) {
            $explode = explode('@', $value);
            return in_array($explode[1], ['gmail.com', 'yahoo.com', 'yahoo.co.id', 'yahoo.co.jp', 'yahoo.co.uk', 'yahoo.com.au', 'yahoo.com.cn', 'yahoo.com.hk', 'yahoo.com.sg', 'yahoo.de', 'yahoo.fr', 'yahoo.co.in', 'yahoo.com.mx', 'yahoo.com.my', 'yahoo.com.ph', 'yahoo.com.sg', 'yahoo.com.tw', 'yahoo.co.th', 'yahoo.co.uk', 'yahoo.com.vn', 'yahoo.com.ar', 'yahoo.com.au', 'yahoo.com.br', 'yahoo.com.co', 'yahoo.com.ec', 'yahoo.com.pe', 'yahoo.com.pr', 'yahoo.com.py', 'yahoo.com.ve', 'yahoo.es', 'yahoo.fr', 'yahoo.it', 'yahoo.nl', 'yahoo.no', 'yahoo.se', 'yahoo.com.ar', 'yahoo.com.au', 'yahoo.com.br', 'yahoo.com.co', 'yahoo.com.ec', 'yahoo.com.pe', 'yahoo.com.pr', 'yahoo.com.py', 'yahoo.com.ve', 'yahoo.es', 'yahoo.fr', 'yahoo.it', 'yahoo.nl', 'yahoo.no', 'yahoo.se', 'yahoo.com.ar', 'yahoo.com.au', 'yahoo.com.br', 'yahoo.com.co', 'yahoo.com.ec', 'yahoo.com.pe', 'yahoo.com.pr', 'yahoo.com.py', 'yahoo.com.ve', 'yahoo.es', 'yahoo.fr', 'yahoo.it', 'yahoo.nl', 'yahoo.no', 'yahoo.se', 'yahoo.com.ar', 'yahoo.com.au', 'yahoo.com.br', 'yahoo.com.co', 'yahoo.com.ec', 'yahoo.com.pe', 'yahoo.com.pr', 'yahoo.com.py', 'yahoo.com.ve', 'yahoo.es', 'yahoo.fr', 'yahoo.it', 'yahoo']);
        });
    }
}

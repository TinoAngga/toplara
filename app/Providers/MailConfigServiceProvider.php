<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // config(['mail.mailers.smtp.username' => getConfig('mail_username')]);
		// config(['mail.mailers.smtp.password' => getConfig('mail_password')]);
		// config(['mail.mailers.smtp.encryption' => getConfig('mail_encryption')]);
		// config(['mail.mailers.smtp.port' => getConfig('mail_port')]);
		// config(['mail.mailers.smtp.host' => getConfig('mail_host')]);
		// config(['mail.from.address' => getConfig('mail_from')]);
		// config(['mail.from.name' => getConfig('title')]);
    }
}

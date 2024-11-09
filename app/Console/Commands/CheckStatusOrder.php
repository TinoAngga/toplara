<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CheckStatusOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_status_orders:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Order check status sent to provider');
        \Log::info('cronjob check status order provider is running');
        $orders = Order::select('id')->where([
            ['status', '=', 'proses'],
            ['is_paid', '=', 1],
            ['provider_order_id', '!=', null],
        ])
        ->inRandomOrder()
        ->limit(20)
        ->get();
        // $orders = Order::where(['status' => 'proses', 'is_paid' => 1])
        //     ->where('provider_order_id', '!=', null)
        //     ->inRandomOrder()
        //     ->limit(20)
        //     ->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                (new \App\Services\Primary\Order\CheckStatusProviderService())->handle($order);
                $this->info('Order check status sent to provider: ' . $order->id);
                flush();
            }
        }
    }
}

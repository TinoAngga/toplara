<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class SendOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_orders:cron';

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
        \Log::info('cronjob order sent to provider is running');
        $orders = Order::select('id')
        ->where([
            ['status', '=', 'pending'],
            ['is_paid', '=', 1],
            ['provider_order_id', '=', null],
        ])
        ->inRandomOrder()
        ->limit(20)
        ->get();
        // $orders = Order::where(['status' => 'pending', 'provider_order_id' => null, 'is_paid' => 1])
        //     ->inRandomOrder()
        //     ->limit(20)
        //     ->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $response = (new \App\Services\Primary\Order\SendToProviderService())->handle($order->id);
                if ($response['status'] == false) continue;
                $this->info('Order sent to provider: ' . $order->id);
                flush();
            }
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Libraries\Curl;
use App\Models\Provider;
use App\Services\Primary\Order\SendToProviderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderToProvider
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        try {
            (new SendToProviderService())->handle($order->id);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}

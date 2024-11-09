<?php

namespace App\Jobs;

use App\Libraries\WhatsappGateway\AxSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderAlertNotifyMail;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mail;
class OrderAlertNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // \Log::info("queue order alert notify");
        // Mail::send(new \App\Mail\OrderAlertNotifyMail($this->order));

        // Log::info("queue order alert notify");
        // $getMessage = getConfig('whatsapp_gateway_place_order_text');
        // $category = ServiceCategory::find($this->order->service->service_category_id);
        // $target = $this->order->data;
        // if ($this->order->additional_data) $target .=  ' ( ' . $this->order->additional_data . ' ) ';
        // $paymentNote = convertString($this->order->payment->information, $this->order->price);
        // $setMessage = strtr($getMessage, [
        //     '[INVOICE]' => $this->order->invoice,
        //     '[SERVICE]' => $category->name . ' - ' . $this->order->service->name,
        //     '[DATA]' => $target,
        //     '[PAYMENT]' => $this->order->payment->name,
        //     '[PAYMENT_NOTE]' => $paymentNote ?? '',
        //     '[EXPIRED_AT]' => format_datetime(Carbon::parse($this->order->created_at)->addHours(24)),
        //     '[PRICE]' => 'Rp ' . currency($this->order->price),
        //     '[NOTE]' => $this->order->provider_order_description ?? '',
        //     '[STATUS]' => '*' . strtoupper($this->order->status) . '*'
        // ]);
        // $invoiceTemplate = ['url|INVOICE ' .$this->order->invoice. '|' . route('order.invoice', $this->order->invoice)];
        // (new AxSender())->sendWithTemplate($setMessage, [
        //     'target' => $this->order->whatsapp_order,
        //     'template' => $invoiceTemplate
        // ]);
    }
}

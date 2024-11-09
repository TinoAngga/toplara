<?php

namespace App\Jobs;

use App\Libraries\WhatsappGateway\AxSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderNotifyMail;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mail;

class OrderNotifyJob implements ShouldQueue
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
        Log::info("queue order notify");
        $getMessageMember = getConfig('whatsapp_gateway_place_order_text');
        $getMessageAdmin = getConfig('whatsapp_gateway_order_admin_text');
        $category = ServiceCategory::find($this->order->service->service_category_id);
        $target = $this->order->data;
        if ($this->order->additional_data) $target .=  ' ( ' . $this->order->additional_data . ' )';
        $paymentNote = convertString($this->order->payment->information, $this->order->price);

        # SET FOR MEMBER
        $setMessageMember = strtr($getMessageMember, [
            '[CONTACT]' => $this->order->whatsapp_order,
            '[USERNAME]' => $this->order->user->username,
            '[INVOICE]' => $this->order->invoice,
            '[SERVICE]' => $category->name . ' - ' . $this->order->service->name,
            '[TARGET]' => $target,
            '[DATA]' => $this->order->data,
            '[ADDITIONAL_DATA]' => $this->order->additional_data ?? '-',
            '[PAYMENT]' => $this->order->payment->name,
            '[PAYMENT_NOTE]' => $paymentNote ?? '',
            '[EXPIRED_AT]' => format_datetime(Carbon::parse($this->order->created_at)->addHours(24)),
            '[PRICE]' => 'Rp ' . currency($this->order->price),
            '[NOTE]' => $this->order->provider_order_description ?? '',
            '[STATUS]' => '*' . strtoupper($this->order->status) . '*',
            '[PAYMENT_STATUS]' => $this->order->is_paid == 0 ? 'BELUM LUNAS' : 'LUNAS'
        ]);
        # SET FOR ADMIN
        $setMessageAdmin = strtr($getMessageAdmin, [
            '[CONTACT]' => $this->order->whatsapp_order,
            '[USERNAME]' => $this->order->user->username,
            '[INVOICE]' => $this->order->invoice,
            '[SERVICE]' => $category->name . ' - ' . $this->order->service->name,
            '[TARGET]' => $target,
            '[DATA]' => $this->order->data,
            '[ADDITIONAL_DATA]' => $this->order->additional_data ?? '-',
            '[PAYMENT]' => $this->order->payment->name,
            '[PAYMENT_NOTE]' => $paymentNote ?? '',
            '[EXPIRED_AT]' => format_datetime(Carbon::parse($this->order->created_at)->addHours(24)),
            '[PRICE]' => 'Rp ' . currency($this->order->price),
            '[NOTE]' => $this->order->provider_order_description ?? '',
            '[STATUS]' => '*' . strtoupper($this->order->status) . '*',
            '[PAYMENT_STATUS]' => $this->order->is_paid == 0 ? 'BELUM LUNAS' : 'LUNAS'
        ]);
        $invoiceTemplateMember = ['url|INVOICE|' . route('order.invoice', $this->order->invoice)];
        // $invoiceTemplateAdmin = ['url|INVOICE ' .$this->order->invoice. '|' . route('order.invoice', $this->order->invoice)];

        // #SEND MESSAGE ADMIN
        // if ($this->order->provider->is_manual == 1 AND in_array($this->order->status, ['pending', 'proses']) == true) {
        //     (new AxSender())->sendMessage($setMessageAdmin, [
        //         'target' => getConfig('whatsapp_gateway_admin_target_number')
        //     ]);
        // }
        // print 'success send message admin';
        // # SEND MESSAGE MEMBER
        // (new AxSender())->sendWithTemplate($setMessageMember, [
        //     'target' => $this->order->whatsapp_order,
        //     'template' => $invoiceTemplateMember
        // ]);
        // print 'success send message';
    }
}

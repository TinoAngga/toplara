<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAlertNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Peringatan Pembayaran Pesanan Baru #' . $this->order->invoice)
            ->to($this->order->email_order)
			->from(env('MAIL_FROM_ADDRESS', 'no-reply@kiosratu.com'), env('MAIL_FROM_NAME', 'Notif KiosRatu'))
            ->view('mail.notify.alert-order-paid', ['order' => $this->order]);
    }
}

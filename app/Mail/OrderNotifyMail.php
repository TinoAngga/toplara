<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // Ubah dari 'protected' menjadi 'public'

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
        \Log::info('OrderNotifyMail for invoice: ' . $this->order->invoice);
        
        return $this->subject('Pesanan Anda Telah Sukses #' . $this->order->invoice)
                    ->from(env('MAIL_FROM_ADDRESS', 'no-reply@kiosratu.com'), env('MAIL_FROM_NAME', 'Notif KiosRatu')) // Ambil dari .env
                    ->view('mail.notify.order')
                    ->with(['order' => $this->order]);  // Data yang diteruskan ke view
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $deposit;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Permintaan Deposit #' . $this->deposit->invoice)
            ->to($this->deposit->user->email)
			->from(config('mail.from.address'), config('mail.from.name'))
            ->view('mail.notify.deposit', ['deposit' => $this->deposit]);
    }
}

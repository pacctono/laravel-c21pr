<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class Cumpleano extends Mailable
{
    use Queueable, SerializesModels;

    public $asesor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $asesor)
    {
        $this->asesor = $asesor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asesor.cumpleano')
                ->subject('Feliz cumpleaño')
                ->from('puentereal@centurt21.com.ve', env('APP_NAME'));
    }
}

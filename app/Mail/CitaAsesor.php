<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contacto;

class CitaAsesor extends Mailable
{
    use Queueable, SerializesModels;

    public $contacto;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contacto $contacto)
    {
        $this->contacto = $contacto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asesor.cita')
                ->subject('Su cita')
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

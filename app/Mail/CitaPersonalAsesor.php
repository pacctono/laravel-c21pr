<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AgendaPersonal;

class CitaPersonalAsesor extends Mailable
{
    use Queueable, SerializesModels;

    public $modelo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AgendaPersonal $cita)
    {
        $this->modelo = $cita;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asesor.cita')
                ->subject('Cita personal con ' . $this->modelo->name)
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

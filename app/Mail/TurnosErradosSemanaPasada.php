<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use App\Turno;

class TurnosErradosSemanaPasada extends Mailable
{
    use Queueable, SerializesModels;

    public $turnos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Collection $turnos)
    {
        $this->turnos = $turnos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.turnos.erradosSemanaPasada')
                ->subject('Turnos con problemas de cumplimiento')
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

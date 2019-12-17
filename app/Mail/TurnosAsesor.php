<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use App\User;

class TurnosAsesor extends Mailable
{
    use Queueable, SerializesModels;

    public $asesor;
    public $turnos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $asesor, Collection $turnos)
    {
        $this->asesor = $asesor;
        $this->turnos = $turnos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.turnos.turnos')
                ->subject('Sus turnos')
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

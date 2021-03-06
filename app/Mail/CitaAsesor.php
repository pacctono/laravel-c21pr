<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contacto;       // Este 'name space' no es necesario. No se usa el modelo, directamente.
use App\AgendaPersonal; // Este 'name space' no es necesario. No se usa el modelo, directamente.

class CitaAsesor extends Mailable
{
    use Queueable, SerializesModels;

    public $modelo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($modelo, $id)
    {
        $nombreModelo = "\App\\$modelo";
        $this->modelo = $nombreModelo::findOrFail($id); // Si falla produce 'ModelNotFoundException'.
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asesor.cita')
                ->subject('Cita con su contacto inicial ' . $this->modelo->name)
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

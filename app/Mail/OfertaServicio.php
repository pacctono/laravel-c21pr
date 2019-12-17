<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contacto;
use App\User;

class OfertaServicio extends Mailable
{
    use Queueable, SerializesModels;

    public $contacto;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contacto $contacto, User $user)
    {
        $this->contacto = $contacto;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contactos.ofertaServicio')
                ->subject('Oferta de Servicio')
//                ->attach('/ruta/a/ofertaServicio', [
//                    'as' => 'ofertaServicio.pdf',
//                    'mime' => 'application/pdf',
//                ])
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

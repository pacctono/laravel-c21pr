<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Propiedad;

class ReporteCierre extends Mailable
{
    use Queueable, SerializesModels;

    public $propiedad;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Propiedad $propiedad)
    {
        $this->propiedad = $propiedad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.propiedad.ReporteCierre')
                ->subject('Reporte de Cierre' . $this->propiedad->nombre)
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

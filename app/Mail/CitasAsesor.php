<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class CitasAsesor extends Mailable
{
    use Queueable, SerializesModels;

    public $asesor;
    public $desde;
    public $hasta;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $asesor, $desde=null, $hasta=null)
    {
        $this->asesor = $asesor;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asesor.citas')
                ->subject('Sus citas')
                ->from('puentereal@centurt21.com.ve', 'Century21 Puente Real');
    }
}

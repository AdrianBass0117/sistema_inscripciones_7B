<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    public $inscripcion;
    public $estado;

    /**
     * Create a new message instance.
     */
    public function __construct(Inscripcion $inscripcion, string $estado)
    {
        $this->inscripcion = $inscripcion;
        $this->estado = $estado; // 'pendiente', 'aceptado', 'rechazado'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '';
        switch ($this->estado) {
            case 'pendiente':
                $subject = 'Hemos recibido tu solicitud de inscripción';
                break;
            case 'aceptado':
                $subject = '¡Tu inscripción ha sido aceptada!';
                break;
            case 'rechazado':
                $subject = 'Actualización sobre tu inscripción';
                break;
        }
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.estado_inscripcion',
        );
    }
}
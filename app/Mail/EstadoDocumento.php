<?php

namespace App\Mail;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoDocumento extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;
    public $estado;
    public $motivoRechazo;

    /**
     * Create a new message instance.
     */
    public function __construct(Documento $documento, string $estado, string $motivoRechazo = null)
    {
        $this->documento = $documento;
        $this->estado = $estado; // 'aceptado' o 'rechazado'
        $this->motivoRechazo = $motivoRechazo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->estado === 'aceptado'
            ? 'Tu documento ha sido aprobado'
            : 'Acción requerida: Tu documento fue rechazado';
            
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
            view: 'emails.estado_documento',
        );
    }
}
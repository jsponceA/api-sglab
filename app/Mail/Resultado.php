<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use function Laravel\Prompts\text;

class Resultado extends Mailable
{
    use Queueable, SerializesModels;

    protected  $correoDestino;
    protected $resultado;
    protected $pdfContent;
    public function __construct($correoDestino,$resultado,string $pdfContent)
    {
        $this->correoDestino = $correoDestino;
        $this->resultado = $resultado;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config("mail.from.address"), config("mail.from.name")),
            to: [new Address($this->correoDestino, $this->resultado?->apenom)],
            subject: "Laboratorio SGLAB'S - RESULTADO DE EXAMEN: {$this->resultado?->ticket}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.resultado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'reporte_analisis.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

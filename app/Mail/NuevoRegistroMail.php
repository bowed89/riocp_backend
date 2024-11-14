<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevoRegistroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request; 

    public function __construct($request)
{
        $this->request = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud para certificado RIOCP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nuevo_registro',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
